<?php
/**
 * BhCache — invalidate cache Bảng hàng (Redis version-bump). Ở models/ để AUTOLOAD cả
 * context WEB lẫn API (core/core.php + api/init.php spl_autoload cùng trỏ models/).
 * Key PHẢI khớp reader api/lib/stock_cache.php:
 *   data   = "bh:ver:p:{pid}"     (bh_ver)   — đổi status/giá 1 căn
 *   struct = "bh:struct:p:{pid}"  (bh_sver)  — sửa toà/phân khu/layout
 * Bump = INCR (O(1)); key cũ tự mồ côi, hết hạn theo TTL. Fail-soft: Redis chết KHÔNG chặn writer.
 */
class BhCache {
    /** Bump version DATA → vô hiệu matrix/lots/facets/unit/buildings của dự án (key nhúng v{ver}). */
    public static function bump($projectId){
        $projectId = (int) $projectId;
        if ($projectId <= 0) return;
        try {
            $clsCache = new Cache();
            $clsCache->increment("bh:ver:p:{$projectId}");
        } catch (\Throwable $e) { /* Redis chết: TTL backstop lo, không chặn ghi */ }
    }

    /** Bump version CẤU TRÚC → vô hiệu matrix + buildings của dự án (key nhúng sv{sver}). */
    public static function bumpStruct($projectId){
        $projectId = (int) $projectId;
        if ($projectId <= 0) return;
        try {
            $clsCache = new Cache();
            $clsCache->increment("bh:struct:p:{$projectId}");
        } catch (\Throwable $e) { /* fail-soft */ }
    }
}
?>
