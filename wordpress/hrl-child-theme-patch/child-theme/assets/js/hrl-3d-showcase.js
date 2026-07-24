/**
 * HRL — 3D Showcase (Three.js particle field)
 * Wyciągnięte z inline <script> w page-3d-showcase.php.
 * Ładowane przez wp_enqueue_script z zależnością od 'threejs',
 * dzięki czemu THREE jest zawsze dostępne w momencie startu.
 */
(function () {
    'use strict';

    function init() {
        var container = document.getElementById('three-canvas');
        if (!container || typeof THREE === 'undefined') {
            return;
        }

        // Szanuj preferencję ograniczenia animacji.
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return;
        }

        try {
            var scene    = new THREE.Scene();
            var camera   = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
            var renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });

            renderer.setSize(window.innerWidth, window.innerHeight);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
            container.appendChild(renderer.domElement);

            var geo   = new THREE.BufferGeometry();
            var verts = new Float32Array(3000);
            for (var i = 0; i < 3000; i++) {
                verts[i] = (Math.random() - 0.5) * 40;
            }
            geo.setAttribute('position', new THREE.BufferAttribute(verts, 3));

            var mat    = new THREE.PointsMaterial({ color: 0xC8A96E, size: 0.06, transparent: true, opacity: 0.7 });
            var points = new THREE.Points(geo, mat);
            scene.add(points);
            camera.position.z = 12;

            var rafId = null;
            var running = true;

            function animate() {
                if (!running) { return; }
                rafId = requestAnimationFrame(animate);
                points.rotation.y += 0.001;
                points.rotation.x += 0.0005;
                renderer.render(scene, camera);
            }
            animate();

            // Wstrzymaj renderowanie, gdy karta jest w tle — oszczędza CPU/baterię.
            document.addEventListener('visibilitychange', function () {
                if (document.hidden) {
                    running = false;
                    if (rafId) { cancelAnimationFrame(rafId); }
                } else if (!running) {
                    running = true;
                    animate();
                }
            });

            var resizeTimer = null;
            window.addEventListener('resize', function () {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function () {
                    camera.aspect = window.innerWidth / window.innerHeight;
                    camera.updateProjectionMatrix();
                    renderer.setSize(window.innerWidth, window.innerHeight);
                }, 150);
            });
        } catch (e) {
            console.warn('Three.js init failed:', e);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
