<div class="firework">
	<canvas id="fire-work" width="1920" height="948"></canvas>
</div>
{literal}
<style type="text/css">
	.firework {
		position: fixed;
		z-index: 99;
		top: 0; left: 0;
		pointer-events: none;
	}
	.firework canvas {
		padding: 0;
		margin: 0;
		left: 0;
		top: 0;
		right: 0;
		bottom: 0;
		position: absolute;
		cursor: crosshair;
		display: block;
		z-index: 99;
		pointer-events: none;
	}
</style>
<script type="text/javascript">
	$(function() {
		window.requestAnimFrame = function() {
			return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || function(n) {
				window.setTimeout(n, 1e3 / 60)
			}
		}();
		function fireworksPlugin(n) {
			// Hàm chuyển đổi mã hex sang giá trị HSL
			function hexToHSL(hex) {
				hex = hex.replace(/^#/, '');
				let bigint = parseInt(hex, 16);
				let r = (bigint >> 16) & 255;
				let g = (bigint >> 8) & 255;
				let b = bigint & 255;
				r /= 255, g /= 255, b /= 255;
				let max = Math.max(r, g, b), min = Math.min(r, g, b);
				let h, s, l = (max + min) / 2;
				if (max === min) {
					h = s = 0; // achromatic
				} else {
					let d = max - min;
					s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
					switch (max) {
						case r:
							h = (g - b) / d + (g < b ? 6 : 0);
							break;
						case g:
							h = (b - r) / d + 2;
							break;
						case b:
							h = (r - g) / d + 4;
							break;
					}
					h /= 6;
				}
				return { h: Math.round(h * 360), s: Math.round(s * 100), l: Math.round(l * 100) };
			}
			function r(n, t) {
				return Math.random() * (t - n) + n
			}
			function w(n, t, i, r) {
				var u = n - i
					, f = t - r;
				return Math.sqrt(Math.pow(u, 2) + Math.pow(f, 2))
			}
			function h(n, t, i, u) {
				for (this.x = n,
						 this.y = t,
						 this.sx = n,
						 this.sy = t,
						 this.tx = i,
						 this.ty = u,
						 this.distanceToTarget = w(n, t, i, u),
						 this.distanceTraveled = 0,
						 this.coordinates = [],
						 this.coordinateCount = 3; this.coordinateCount--; )
					this.coordinates.push([this.x, this.y]);
				this.angle = Math.atan2(u - t, i - n);
				this.speed = 1.5;
				this.acceleration = 1.03;
				this.brightness = r(60, 70);
				this.targetRadius = 1.5
			}
			function a(n, t) {
				for (this.x = n,
						 this.y = t,
						 this.coordinates = [],
						 this.coordinateCount = 5; this.coordinateCount--; )
					this.coordinates.push([this.x, this.y]);
				this.angle = r(0, Math.PI * 2);
				this.speed = r(1, 10);
				this.friction = .93;
				this.gravity = 1;
				this.hue = r(v - 15);
				this.brightness = r(30, 80);
				this.alpha = 2;
				this.decay = r(.015, .07)
			}
			function g(n, t) {
				for (var i = 80; i--; )
					o.push(new a(n,t))
			}
			function b() {
				var n, i;
				for (requestAnimFrame(b),
						 t.globalCompositeOperation = "destination-out",
						 t.fillStyle = "rgba(0, 0, 0, 0.5)",
						 t.fillRect(0, 0, f, e),
						 t.globalCompositeOperation = "lighter",
						 n = u.length; n--; )
					u[n].draw(),
						u[n].update(n);
				for (i = o.length; i--; )
					o[i].draw(),
						o[i].update(i);
				l >= d ? s || (u.push(new h(f / 2,e,r(0, f),r(0, e / 2))),
					l = 0) : l++;
				c >= k ? s && (u.push(new h(f / 2,e,y,p)),
					c = 0) : c++
			}
			var i = n, t = i.getContext("2d"), f = window.innerWidth, e = window.innerHeight, 
				u = [], o = [], v = hexToHSL("#ff0000").h, k = 10, c = 0, d = 15, l = 0, s = !1, y, p;
			if (matchMedia('only screen and (max-width: 550px)').matches) {
				d = 50;
			}
			i.width = f;
			i.height = e;
			h.prototype.update = function(n) {
				this.coordinates.pop();
				this.coordinates.unshift([this.x, this.y]);
				this.targetRadius < 8 ? this.targetRadius += .3 : this.targetRadius = 1;
				this.speed *= this.acceleration;
				var t = Math.cos(this.angle) * this.speed
					, i = Math.sin(this.angle) * this.speed;
				this.distanceTraveled = w(this.sx, this.sy, this.x + t, this.y + i);
				this.distanceTraveled >= this.distanceToTarget ? (g(this.tx, this.ty),
					u.splice(n, 1)) : (this.x += t,
					this.y += i)
			};
			h.prototype.draw = function() {
				t.beginPath();
				t.moveTo(this.coordinates[this.coordinates.length - 1][0], this.coordinates[this.coordinates.length - 1][1]);
				t.lineTo(this.x, this.y);
				t.strokeStyle = "hsl(" + v + ", 100%, " + this.brightness + "%)";
				t.stroke();
				t.beginPath();
				t.arc(this.tx, this.ty, this.targetRadius, 0, Math.PI * 2);
				t.stroke()
			};
			a.prototype.update = function(n) {
				this.coordinates.pop();
				this.coordinates.unshift([this.x, this.y]);
				this.speed *= this.friction;
				this.x += Math.cos(this.angle) * this.speed;
				this.y += Math.sin(this.angle) * this.speed + this.gravity;
				this.alpha -= this.decay;
				this.alpha <= this.decay && o.splice(n, 1)
			};
			a.prototype.draw = function() {
				t.beginPath();
				t.moveTo(this.coordinates[this.coordinates.length - 1][0], this.coordinates[this.coordinates.length - 1][1]);
				t.lineTo(this.x, this.y);
				t.strokeStyle = "hsla(" + this.hue + ", 100%, " + this.brightness + "%, " + this.alpha + ")";
				t.stroke()
			};
			i.addEventListener("mousemove", function(n) {
				y = n.pageX - i.offsetLeft;
				p = n.pageY - i.offsetTop
			});
			i.addEventListener("mousedown", function(n) {
				n.preventDefault();
				s = !0
			});
			i.addEventListener("mouseup", function(n) {
				n.preventDefault();
				s = !1
			});
			window.onload = b
		}
		i = document.getElementById("fire-work");
		fireworksPlugin(i);
		let countdown = 15;
		let intervalId = setInterval(function() {
			if (countdown <= 0) {
				clearInterval(intervalId);
				$("#fire-work").hide();
			}
			countdown -= 1;
		}, 1000);
	});
</script>
{/literal}