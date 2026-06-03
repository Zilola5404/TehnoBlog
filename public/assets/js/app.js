// Файл отвечает за интерактивность карточек и визуальные эффекты на странице.
const cards = document.querySelectorAll('.post-card');

cards.forEach((card) => {
  card.addEventListener('pointermove', (event) => {
    if (window.matchMedia('(hover: none)').matches) {
      return;
    }

    const rect = card.getBoundingClientRect();
    const x = (event.clientX - rect.left) / rect.width - 0.5;
    const y = (event.clientY - rect.top) / rect.height - 0.5;

    card.style.transform = `perspective(1000px) rotateX(${y * -10}deg) rotateY(${x * 10}deg) translateY(-8px)`;
  });

  card.addEventListener('pointerleave', () => {
    card.style.transform = '';
  });
});

// ---------------------------
// Three.js — 3D фон и движущиеся фигуры
// ---------------------------

const canvas = document.getElementById('three-bg');

if (canvas && window.THREE) {
  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(55, window.innerWidth / window.innerHeight, 0.1, 100);
  const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
  const pointer = { x: 0, y: 0 };

  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.8));
  renderer.setSize(window.innerWidth, window.innerHeight);
  camera.position.z = 8;

  const cyan = new THREE.MeshBasicMaterial({
    color: 0x00f2fe,
    wireframe: true,
    transparent: true,
    opacity: 0.38,
  });

  const pink = new THREE.MeshBasicMaterial({
    color: 0xf355da,
    wireframe: true,
    transparent: true,
    opacity: 0.32,
  });

  const violet = new THREE.MeshBasicMaterial({
    color: 0x7000ff,
    wireframe: true,
    transparent: true,
    opacity: 0.3,
  });

  const meshes = [
    new THREE.Mesh(new THREE.IcosahedronGeometry(1.35, 1), cyan),
    new THREE.Mesh(new THREE.TorusGeometry(1.05, 0.24, 16, 72), pink),
    new THREE.Mesh(new THREE.BoxGeometry(1.65, 1.65, 1.65), violet),
  ];

  meshes[0].position.set(-3.2, 1.5, -1.2);
  meshes[1].position.set(3.4, 0.8, -1.6);
  meshes[2].position.set(1.2, -2.1, -1);
  meshes.forEach((mesh) => scene.add(mesh));

  window.addEventListener('pointermove', (event) => {
    pointer.x = event.clientX / window.innerWidth - 0.5;
    pointer.y = event.clientY / window.innerHeight - 0.5;
  });

  window.addEventListener('resize', () => {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
  });

  const animate = () => {
    meshes.forEach((mesh, index) => {
      mesh.rotation.x += 0.002 + index * 0.001;
      mesh.rotation.y += 0.003 + index * 0.001;
      mesh.position.x += (pointer.x * -1.2 - mesh.position.x * 0.015) * 0.012;
      mesh.position.y += (pointer.y * 1.2 - mesh.position.y * 0.015) * 0.012;
    });

    renderer.render(scene, camera);
    requestAnimationFrame(animate);
  };

  animate();
}
/* ───  Плавная анимация блоков ───────────────────────────── */
(function () {
  'use strict';

  var STAGGER_MS = 150;  // задержка между карточками в гриде
  var SEQ_MS     = 180;  // задержка для последовательных блоков (пост)

  function initAnimations() {

    // 1. Единый hero-блок страницы: hero / category-header / post__header
    var hero = document.querySelector('.hero, .category-header, .post__header');
    if (hero) {
      hero.classList.add('anim-hero');
      setTimeout(function () { hero.classList.add('anim-in'); }, 60);
    }

    // 2. Последовательные блоки внутри статьи: картинка → тело
    document.querySelectorAll('.post__image, .post__body').forEach(function (el, i) {
      el.classList.add('anim-seq');
      el.style.transitionDelay = (i * SEQ_MS) + 'ms';
    });

    // 3. Section headings (главная + похожие статьи)
    document.querySelectorAll('.section-heading').forEach(function (el) {
      el.classList.add('anim-section');
    });

    // 4. Post cards — stagger внутри каждого грида
    document.querySelectorAll('.post-grid').forEach(function (grid) {
      grid.querySelectorAll('.post-card').forEach(function (card, i) {
        card.classList.add('anim-card');
        card.style.transitionDelay = (i * STAGGER_MS) + 'ms';
      });
    });

    // 5. Пагинация
    var pagination = document.querySelector('.pagination');
    if (pagination) pagination.classList.add('anim-seq');
    var pagSummary = document.querySelector('.pagination-summary');
    if (pagSummary) {
      pagSummary.classList.add('anim-seq');
      pagSummary.style.transitionDelay = SEQ_MS + 'ms';
    }

    // 6. IntersectionObserver — для всего кроме hero
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('anim-in');
        io.unobserve(entry.target);
      });
    }, {
      threshold: 0.08,
      rootMargin: '0px 0px -50px 0px'
    });

    document.querySelectorAll(
      '.anim-section, .anim-card, .anim-seq'
    ).forEach(function (el) { io.observe(el); });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAnimations);
  } else {
    initAnimations();
  }

})();