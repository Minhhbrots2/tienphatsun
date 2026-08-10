<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 by Future Group.         # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
class DB {
    /** @var object ADOdb connection object */
    protected $dbconn;
    protected $select = '*';
    protected $from = '';
    protected $where_parts = [];
    protected $join = [];
    protected $group_by = '';
    protected $having = '';
    protected $order_by_parts = [];
    protected $limit = '';
    protected $offset = '';
    protected $query = '';
    protected $cache_enabled = false;
    protected $cache_seconds = 60;
    protected $alias = '';
    protected $distinct = false;
    protected $set_parts = [];
	/** @var array Registered macros */
	protected static $macros = [];
    
	/**
     * DB constructor.
     */
    public function __construct() {
        global $dbconn;
        $this->dbconn = $dbconn;
    }
	
    /**
     * Clone current query builder state
     * @return self
     */
    public function copy() {
        $clone = clone $this;
        return $clone;
    }

	/**
	 * Escape identifier (e.g., column or table name)
	 * @param string $str
	 * @return string
	 */
	protected function escape_identifier(string $str): string {
		$str = trim($str);
		if ($str === '*' || preg_match('/\w+\s*\(.*\)/', $str)) {
			return $str; // Don't escape * or SQL functions
		}
        // Handle aliases: column AS alias or column alias
		if (stripos($str, ' as ') !== false) {
			[$col, $alias] = preg_split('/\\s+as\\s+/i', $str);
			return $this->escape_identifier($col) . ' AS ' . $this->escape_identifier($alias);
		}
        // Escape dot notation: table.column
		if (strpos($str, '.') !== false) {
			return implode('.', array_map([$this, 'escape_identifier'], explode('.', $str)));
		}
		return '`' . str_replace('`', '``', $str) . '`';
	}

    /**
	 * Escape order_identifier (e.g., column or table name)
	 * @param string $str
	 * @return string
	 */
    protected function escape_order_identifier(string $str): string {
        $str = trim($str);
        if (preg_match('/^(.*?)\s+(asc|desc)$/i', $str, $matches)) {
            $column = $this->escape_identifier($matches[1]);
            $direction = strtoupper($matches[2]);
            return "$column $direction";
        }
        return $this->escape_identifier($str);
    }
	/**
	 * Escape a SQL expression, preserving operators
	 * @param string $expr
	 * @return string
	 */
	protected function escape_expression(string $expr): string {
		$expr = trim($expr);

		// Không escape nếu là SQL function (SUM(), COUNT(), etc.)
		if ($expr === '*' || preg_match('/^\w+\s*\(.*\)$/', $expr)) {
			return $expr;
		}
		// Detect common binary operators and split
		$pattern = '/\s*(=|<>|!=|<=|>=|<|>|LIKE|IS NOT|IS)\s*/i';
		if (preg_match($pattern, $expr, $matches)) {
			[$left, $right] = preg_split($pattern, $expr, 2);
			$op = $matches[0];
			return $this->escape_identifier($left) . ' ' . strtoupper(trim($op)) . ' ' . $this->escape_identifier($right);
		}
		// Handle dot notation or alias
		return $this->escape_identifier($expr);
	}
	/**
     * Set SELECT fields with optional escaping
     * @param string|array $fields
     * @param bool $escape
     * @return $this
     */
	public function select($fields = '*', $escape = true) {
		if (is_array($fields)) {
			$this->select = implode(', ', $escape ? array_map([$this, 'escape_identifier'], $fields) : $fields);
		} else {
			if ($escape && strpos($fields, ',') !== false) {
				$columns = array_map('trim', explode(',', $fields));
				$this->select = implode(', ', array_map([$this, 'escape_identifier'], $columns));
			} else {
				$this->select = $escape ? $this->escape_identifier($fields) : $fields;
			}
		}
		return $this;
	}
	
	/**
	 * Return raw SQL expression (for special cases)
	 * @param string $value
	 * @return string
	 */
	public function raw($value) {
		return $value; // giữ nguyên không escape
	}
	
    /**
     * Set DISTINCT option
     * @return $this
     */
    public function distinct() {
        $this->distinct = true;
        return $this;
    }

    /**
     * Set FROM table with optional escaping
     * @param string $table
     * @param bool $escape
     * @return $this
     */
    public function from($table, $escape = true) {
        $this->from = $escape ? $this->escape_identifier($table) : $table;
        return $this;
    }

	/**
     * Initialize a new instance with a FROM table
     * @param string $table
     * @return static
     */
    public static function table($name, $escape=true) {
        $instance = new static();
        return $instance->from($name, $escape);
    }

	/**
     * Use a subquery as the FROM source
     * @param DB $builder Subquery builder
     * @param string $alias Alias name
     * @return $this
     */
    public function from_subquery(DB $builder, $alias) {
        $sub_sql = $builder->compile_select();
        $this->from = "($sub_sql) AS " . $this->escape_identifier($alias);
        return $this;
    }

    /**
     * Add subquery to SELECT clause
     * @param DB $builder Subquery builder
     * @param string $alias Alias name
     * @return $this
	   $sub = (new DB())->select('user_id')->from('logins')->where('success', 1);
	   $main = (new DB())
			->select('u.name')
			->select_subquery($sub, 'active_users')
			->from('users u');
     */
    public function select_subquery(DB $builder, $alias) {
        $sub_sql = $builder->compile_select();
        if ($this->select === '*') $this->select = '';
        if (!empty($this->select)) $this->select .= ', ';
        $this->select.= "($sub_sql) AS " . $this->escape_identifier($alias);
        return $this;
    }
	
    /**
     * Set alias for the table
     * @param string $alias
     * @return $this
     */
    public function alias($alias) {
        $this->alias = $this->escape_identifier($alias);
        return $this;
    }

    /**
     * Add WHERE condition
     * @param string $field
     * @param mixed $value
     * @param string $operator
     * @return $this
     */
    public function where($field, $value, $operator = '=') {
		$field = $this->escape_identifier($field);
        $value = $this->dbconn->qstr($value);
        $this->where_parts[] = ['type' => 'AND', 'cond' => "$field $operator $value"];
        return $this;
    }

    /**
     * Add OR WHERE condition
     * @param string $field
     * @param mixed $value
     * @param string $operator
     * @return $this
     */
    public function or_where($field, $value, $operator = '=') {
		$field = $this->escape_identifier($field);
        $value = $this->dbconn->qstr($value);
        $this->where_parts[] = ['type' => 'OR', 'cond' => "$field $operator $value"];
        return $this;
    }
	
	/**
	 * Add multiple where conditions via array
	 * @param array $conditions
	 * @return $this
	 */
	public function where_array(array $conditions) {
		foreach ($conditions as $cond) {
			var_dump($cond); die();
			if (is_array($cond) && count($cond) >= 2) {
				
				$this->where($cond[0], $cond[2] ?? '=', $cond[1]);
			}
		}
		return $this;
	}
	
    /**
     * Add WHERE IN condition
     * @param string $key
     * @param array $values
     * @return $this
     */
    public function where_in($key, $values = []) {
        $escaped = array_map([$this->dbconn, 'qstr'], $values);
        $this->where_parts[] = ["type" => "AND", "cond" => "$key IN (" . implode(',', $escaped) . ")"];
        return $this;
    }
	
    /**
     * Add OR WHERE IN condition
     * @param string $key
     * @param array $values
     * @return $this
     */
    public function or_where_in($key, $values = []) {
        $escaped = array_map([$this->dbconn, 'qstr'], $values);
        $this->where_parts[] = ["type" => "OR", "cond" => "$key IN (" . implode(',', $escaped) . ")"];
        return $this;
    }

    /**
     * Add LIKE condition
     * @param string $field
     * @param string $match
     * @param string $side
     * @return $this
     */
    public function like($field, $match, $side = 'both') {
        $match = $side == 'before' ? "%$match" : ($side == 'after' ? "$match%" : "%$match%");
        $match = $this->dbconn->qstr($match);
        $this->where_parts[] = ["type" => "AND", "cond" => "$field LIKE $match"];
        return $this;
    }

    /**
     * Add OR LIKE condition
     * @param string $field
     * @param string $match
     * @param string $side
     * @return $this
     */
    public function or_like($field, $match, $side = 'both') {
        $match = $side == 'before' ? "%$match" : ($side == 'after' ? "$match%" : "%$match%");
        $match = $this->dbconn->qstr($match);
        $this->where_parts[] = ["type" => "OR", "cond" => "$field LIKE $match"];
        return $this;
    }

    /**
     * Add IS NULL condition
     * @param string $field
     * @return $this
     */
    public function where_null($field) {
        $this->where_parts[] = ['type' => 'AND', 'cond' => "$field IS NULL"];
        return $this;
    }

    /**
     * Add IS NOT NULL condition
     * @param string $field
     * @return $this
     */
    public function where_not_null($field) {
        $this->where_parts[] = ['type' => 'AND', 'cond' => "$field IS NOT NULL"];
        return $this;
    }
	
	/**
     * Add WHERE BETWEEN condition
     * @param string $field
     * @param mixed $start
     * @param mixed $end
     * @return $this
     */
    public function where_between($field, $start, $end) {
        $field = $this->escape_identifier($field);
        $start = $this->dbconn->qstr($start);
        $end = $this->dbconn->qstr($end);
        $this->where_parts[] = ['type' => 'AND', 'cond' => "$field BETWEEN $start AND $end"];
        return $this;
    }

    /**
     * Add WHERE NOT BETWEEN condition
     * @param string $field
     * @param mixed $start
     * @param mixed $end
     * @return $this
     */
    public function where_not_between($field, $start, $end) {
        $field = $this->escape_identifier($field);
        $start = $this->dbconn->qstr($start);
        $end = $this->dbconn->qstr($end);
        $this->where_parts[] = ['type' => 'AND', 'cond' => "$field NOT BETWEEN $start AND $end"];
        return $this;
    }
	
	/**
     * Add OR WHERE BETWEEN condition
     * @param string $field
     * @param mixed $start
     * @param mixed $end
     * @return $this
     */
    public function or_where_between($field, $start, $end) {
        $field = $this->escape_identifier($field);
        $start = $this->dbconn->qstr($start);
        $end = $this->dbconn->qstr($end);
        $this->where_parts[] = ['type' => 'OR', 'cond' => "$field BETWEEN $start AND $end"];
        return $this;
    }

    /**
     * Add OR WHERE NOT BETWEEN condition
     * @param string $field
     * @param mixed $start
     * @param mixed $end
     * @return $this
     */
    public function or_where_not_between($field, $start, $end) {
        $field = $this->escape_identifier($field);
        $start = $this->dbconn->qstr($start);
        $end = $this->dbconn->qstr($end);
        $this->where_parts[] = ['type' => 'OR', 'cond' => "$field NOT BETWEEN $start AND $end"];
        return $this;
    }
	
    /**
     * Begin grouping WHERE conditions
     * @param string $type
     * @return $this
     */
	 public function group_start($type = 'AND') {
		$this->where_parts[] = ["type" => strtoupper($type), "cond" => "(", "group" => true];
		return $this;
	}
	
	/**
     * Begin grouping WHERE conditions with OR
     * @return $this
     */
    public function or_group_start() {
        $this->where_parts[] = ["type" => "OR", "cond" => "(", "group" => true];
        return $this;
    }
    /**
     * End grouping WHERE conditions
     * @return $this
     */
    public function group_end() {
        $this->where_parts[] = ["type" => '', "cond" => ")", "group" => true];
        return $this;
    }

    /**
     * Add JOIN clause
     * @param string $table
     * @param string $condition
     * @param string $type
     * @return $this
     */
    public function join($table, $condition, $type = 'INNER', $escape=true) {
		$table = $this->escape_identifier($table);
		if ($escape) {
			$condition = $this->escape_expression($condition);
		}
        $this->join[] = strtoupper($type) . " JOIN {$table} ON {$condition}";
        return $this;
    }

    /**
     * Add GROUP BY clause
     * @param string $field
     * @return $this
     */
    public function group_by($field) {
		$field = $this->escape_identifier($field);
        $this->group_by = "GROUP BY {$field}";
        return $this;
    }

    /**
     * Add HAVING clause
     * @param string $condition
     * @return $this
     */
    public function having($condition) {
        $this->having = "HAVING $condition";
        return $this;
    }

    /**
     * Add ORDER BY clause
     * @param string $field
     * @param string $direction
     * @return $this
     */
    public function order_by($field, $direction = 'ASC') {
        $field = $this->escape_order_identifier($field);
        $direction = strtoupper(trim($direction));
        $this->order_by_parts[] = "$field $direction";
        return $this;
    }

    /**
     * Add ORDER BY RANDOM
     * @return $this
     */
    public function order_by_random() {
        $this->order_by_parts[] = 'RAND()';
        return $this;
    }

    /**
     * Set LIMIT
     * @param int $count
     * @return $this
     */
    public function limit($count) {
        $this->limit = "LIMIT {$count}";
        return $this;
    }

    /**
     * Set OFFSET
     * @param int $start
     * @return $this
     */
    public function offset($start) {
        $this->offset = "OFFSET {$start}";
        return $this;
    }

    /**
     * Enable caching
     * @param int $seconds
     * @return $this
     */
    public function cache_on($seconds = 60) {
        $this->cache_enabled = true;
        $this->cache_seconds = $seconds;
        return $this;
    }

    /**
     * Disable caching
     * @return $this
     */
    public function cache_off() {
        $this->cache_enabled = false;
        return $this;
    }

    /**
     * Execute SELECT query
     * @param string|null $table
     * @return array|false
     */
    public function get($table = null) {
        if ($table) $this->from($table);
        $sql = 'SELECT ' . ($this->distinct ? 'DISTINCT ' : '') . $this->select . ' FROM ' . $this->from;
        if ($this->alias) $sql .= ' AS ' . $this->alias;
        if (!empty($this->join)) {
			$sql .= ' ' . implode(' ', $this->join);
		}
        if (!empty($this->where_parts)) {
			$sql .= ' WHERE ' . $this->build_where_clause();
		}
		if (!empty($this->group_by)) {
            $sql .= ' ' . $this->group_by;
        }
        if (!empty($this->having)) {
            $sql .= ' ' . $this->having;
        }
        if (!empty($this->order_by_parts)) {
            $sql .= ' ORDER BY ' . implode(', ', $this->order_by_parts);
        }
        if (!empty($this->limit)) $sql .= ' ' . $this->limit;
        if (!empty($this->offset)) $sql .= ' ' . $this->offset;
        $this->query = $sql;
        $result = $this->cache_enabled
            ? $this->dbconn->CacheExecute($this->cache_seconds, $sql)
            : $this->dbconn->Execute($sql);

        $this->reset();
        return $result ? $result->GetRows() : false;
    }
	/**
	 * Execute SELECT query and return the first row
	 *
	 * @param string|null $table
	 * @return array|false
	 */
	public function first($table = null) {
		$this->limit(1);
		$result = $this->get($table);
		return !empty($result) ? $result[0] : false;
	}
    /**
     * Execute INSERT query
     * @param string $table
     * @param array $data
     * @return int|false
     */
    public function insert($table, $data) {
        $fields = implode(',', array_keys($data));
        $values = implode(',', array_map([$this->dbconn, 'qstr'], array_values($data)));
        $sql = "INSERT INTO $table ($fields) VALUES ($values)";
        $this->query = $sql;
        $this->dbconn->Execute($sql);
        return $this->dbconn->Insert_ID();
    }

    /**
     * Execute UPDATE query
     * @param string $table
     * @param array $data
     * @return bool
     */
    public function update($table, $data) {
        $set = [];
        foreach ($data as $key => $val) {
            $val = $this->dbconn->qstr($val);
            $set[] = "$key = $val";
        }
        $sql = "UPDATE $table SET " . implode(',', $set);
        if (!empty($this->where_parts)) {
            $sql .= ' WHERE ' . $this->build_where_clause();
        }
        $this->query = $sql;
        $this->reset();
        return $this->dbconn->Execute($sql);
    }

    /**
     * Execute DELETE query
     * @param string $table
     * @return bool
     */
    public function delete($table) {
        $sql = "DELETE FROM $table";
        if (!empty($this->where_parts)) {
            $sql .= ' WHERE ' . $this->build_where_clause();
        }
        $this->query = $sql;
        $this->reset();
        return $this->dbconn->Execute($sql);
    }

    /**
     * Insert multiple rows
     * @param string $table
     * @param array $data_array
     * @return bool
     */
    public function insert_batch($table, $data_array) {
        if (empty($data_array)) return false;
        $fields = array_keys($data_array[0]);
        $values = [];
        foreach ($data_array as $row) {
            $escaped = array_map([$this->dbconn, 'qstr'], $row);
            $values[] = '(' . implode(',', $escaped) . ')';
        }
        $sql = "INSERT INTO $table (" . implode(',', $fields) . ") VALUES " . implode(',', $values);
        $this->query = $sql;
        return $this->dbconn->Execute($sql);
    }

    /**
     * Count total rows matching the conditions
     * @param string|null $table
     * @return int
     */
    public function count_all_results($table = null) {
        if ($table) $this->from($table);
        $sql = "SELECT COUNT(*) AS cnt FROM {$this->from}";
        if (!empty($this->join)) $sql .= ' ' . implode(' ', $this->join);
        if (!empty($this->where_parts)) $sql .= ' WHERE ' . $this->build_where_clause();
        $this->query = $sql;
        $rs = $this->dbconn->Execute($sql);
        $this->reset();
        return $rs ? (int)$rs->fields['cnt'] : 0;
    }

    /**
     * Check if any row exists
     * @param string|null $table
     * @return bool
     */
    public function exists($table = null) {
        $this->limit(1);
        $result = $this->get($table);
        return !empty($result);
    }
	/**
	 * Add conditionally a query clause
	 * @param mixed $condition
	 * @param callable $callback
	 * @return $this
	 * $gender = $_GET['gender'] ?? null;
	 * $users = (new DB())
	 *		->from('users')
	 *		->when($gender, function($q) use ($gender) {
	 *			$q->where('gender', $gender);
	 *		})
	 *		->where('status', 'active')
	 *		->order_by('created_at', 'DESC')
	 *		->get();
	 */
	public function when($condition, callable $callback) {
		if ($condition) {
			$callback($this);
		}
		return $this;
	}
	
    /**
     * Escape a value using ADOdb
     * @param mixed $value
     * @return string
     */
    public function escape($value) {
        return $this->dbconn->qstr($value);
    }

    /**
     * Compile SQL SELECT statement without executing
     * @return string
     */
    public function compile_select() {
        if ($this->from == '') return '';
        $sql = 'SELECT ' . ($this->distinct ? 'DISTINCT ' : '') . $this->select . ' FROM ' . $this->from;
        if ($this->alias) $sql .= ' AS ' . $this->alias;
        if (!empty($this->join)) {
			$sql .= ' ' . implode(' ', $this->join);
		}
		
        if (!empty($this->where_parts)) {
			$sql .= ' WHERE ' . $this->build_where_clause();
		}
        
		if (!empty($this->group_by)) {
            $sql .= ' GROUP BY ' . $this->escape_identifier($this->group_by);
        }

        if (!empty($this->having)) {
            $sql .= ' ' . $this->having;
        }

        if (!empty($this->order_by_parts)) {
            $sql .= ' ORDER BY ' . implode(', ', $this->order_by_parts);
        }
		
        if (!empty($this->limit)) $sql .= ' ' . $this->limit;
        if (!empty($this->offset)) $sql .= ' ' . $this->offset;
        return $sql;
    }
	/**
	 * Get compiled SQL for debugging
	 * @return string
	 */
	public function toSql() {
		return $this->compile_select();
	}
    /**
     * Get last executed query
     * @return string
     */
    public function last_query() {
        return $this->query;
    }

    /**
     * Build WHERE clause from parts
     * @return string
     */
	protected function build_where_clause() {
		$where_sql = '';
		$first = true;
		foreach ($this->where_parts as $part) {
			$type = $part['type'];
			$cond = $part['cond'];
			$is_group = isset($part['group']) && $part['group'];
			
			// Nếu là mở ngoặc (
			if ($is_group && $cond === '(') {
				// Không thêm prefix nếu là phần tử đầu tiên hoặc ngay sau (
				$where_sql .= ($first ? '' : " {$type} ") . '(';
				$first = true; // Reset để điều kiện trong ngoặc không bị thêm prefix lần nữa
				continue;
			}
			
			// Nếu là đóng ngoặc )
			if ($is_group && $cond === ')') {
				$where_sql .= ')';
				$first = false;
				continue;
			}
			
			// Các điều kiện bình thường
			$prefix = $first ? '' : " {$type} ";
			$where_sql .= "{$prefix}{$cond}";
			$first = false;
		}

		return $where_sql;
	}
    protected function build_where_clause_bkc() {
        $where_sql = '';
        $first = true;
		
        foreach ($this->where_parts as $part) {
            if (isset($part['group']) && $part['group']) {
                $where_sql .= ' ' . $part['cond'];
            } else {
                $prefix = $first ? '' : $part['type'] . ' ';
                $where_sql .= ($where_sql ? ' ' : '') . $prefix . $part['cond'];
                $first = false;
            }
        }
        return $where_sql;
    }
	/**
	 * Register a new macro
	 * @param string $name
	 * @param callable $callback
	 * @return void
	 */
	public static function macro(string $name, callable $callback) {
		self::$macros[$name] = $callback;
	}
	
	/**
	 * Get all registered macros
	 * @return array
	 */
    public static function get_macros() {
        return self::$macros;
    }
	
	/**
	 * Handle dynamic method calls into the class.
	 * @param string $method
	 * @param array $arguments
	 * @return mixed
	 * @throws BadMethodCallException
	 */
	public function __call($method, $arguments) {
		if (isset(self::$macros[$method])) {
			return call_user_func_array(self::$macros[$method], array_merge([$this], $arguments));
		}

		throw new BadMethodCallException("Method {$method} does not exist.");
	}
	
	/**
	* Compile a subquery
	* @param callable $callback A callback that receives a new DB instance
	* @param string|null $alias Optional alias
	* @return string
		$builder = new DB();
		$users = $builder
			->from($builder->subquery(function($q) {
				$q->select('id, name')->from('users')->where('status', 'active');
			}, 'active_users'))
			->where('active_users.name', 'like', '%John%')
			->get();
	*/
    public function subquery(callable $callback, string $alias = null): string {
        $sub = new self();
        $callback($sub);
        $sql = '(' . $sub->compile_select() . ')';
        return $alias ? "{$sql} AS " . $this->escape_identifier($alias) : $sql;
    }
	
    /**
     * Reset query state
     * @return void
     */
    protected function reset() {
        $this->select = '*';
        $this->from = '';
        $this->alias = '';
        $this->distinct = false;
        $this->where_parts = [];
        $this->join = [];
        $this->group_by = '';
        $this->having = '';
        $this->order_by_parts = [];
        $this->limit = '';
        $this->offset = '';
		$this->alias = '';
        $this->distinct = false;
		$this->set_parts = [];
    }
}
?>