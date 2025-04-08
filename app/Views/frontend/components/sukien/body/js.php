	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
	<!-- LineIcons CSS thay vì LineIcons JS để tránh lỗi blocker -->
	<link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">
	<!-- Custom JS -->
	<script src="<?= base_url('assets/modules/sukien/js/scripts.js') ?>"></script>
	
	<!-- JavaScript Libraries -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
	<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/2.0.0/countUp.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/parallax.js/1.5.0/parallax.min.js"></script>
	<!-- Custom JavaScript -->
	<script>
		// Initialize AOS
		AOS.init({
			duration: 800,
			once: true
		});
		
		// Initialize Particles.js
		particlesJS('particles-js', {
			particles: {
				number: { value: 80, density: { enable: true, value_area: 800 } },
				color: { value: '#ffffff' },
				shape: { type: 'circle' },
				opacity: { value: 0.5, random: false },
				size: { value: 3, random: true },
				line_linked: { enable: true, distance: 150, color: '#ffffff', opacity: 0.4, width: 1 },
				move: { enable: true, speed: 6, direction: 'none', random: false, straight: false, out_mode: 'out', bounce: false }
			},
			interactivity: {
				detect_on: 'canvas',
				events: {
					onhover: { enable: true, mode: 'repulse' },
					onclick: { enable: true, mode: 'push' },
					resize: true
				}
			},
			retina_detect: true
		});
		
		// Initialize Parallax - kiểm tra tồn tại Parallax trước khi sử dụng
		document.addEventListener('DOMContentLoaded', function() {
			if (typeof Parallax !== 'undefined') {
				const elements = document.querySelectorAll('.floating-shapes span');
				if (elements.length > 0) {
					elements.forEach(shape => {
						new Parallax(shape);
					});
				}
			}
		});
		
		// Initialize CountUp
		document.addEventListener('DOMContentLoaded', function() {
			const counters = document.querySelectorAll('.counter');
			if (counters.length > 0) {
				counters.forEach(counter => {
					const countUp = new CountUp(counter, counter.textContent, {
						duration: 2.5,
						separator: ',',
						decimal: '.'
					});
					countUp.start();
				});
			}
		});
		
		// Smooth Scroll - sửa lỗi selector '#'
		document.addEventListener('DOMContentLoaded', function() {
			document.querySelectorAll('a[href^="#"]').forEach(anchor => {
				anchor.addEventListener('click', function (e) {
					// Kiểm tra selector hợp lệ
					const href = this.getAttribute('href');
					if (href === '#') {
						e.preventDefault();
						return;
					}
					
					try {
						e.preventDefault();
						const target = document.querySelector(href);
						if (target) {
							target.scrollIntoView({
								behavior: 'smooth'
							});
						}
					} catch (error) {
						console.warn('Invalid selector:', href);
					}
				});
			});
		});
		
		// Back to Top Button
		window.addEventListener('scroll', function() {
			const backToTop = document.querySelector('.back-to-top');
			if (backToTop) {
				if (window.pageYOffset > 300) {
					backToTop.style.opacity = '1';
					backToTop.style.visibility = 'visible';
				} else {
					backToTop.style.opacity = '0';
					backToTop.style.visibility = 'hidden';
				}
			}
		});
	</script>

    <?= $this->renderSection('sukien_layout_scripts') ?>