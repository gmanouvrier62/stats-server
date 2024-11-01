// assets/js/router.js
import { createRouter, createWebHistory } from 'vue-router';
import Principale from './components/Principale.vue';
import Resultat from './components/Resultat.vue';

const routes = [
  { path: '/principale', component: Principale },
  { path: '/resultat', component: Resultat },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
