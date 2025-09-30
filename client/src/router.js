import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from './stores/auth';

const Login = () => import('./views/Login.vue');
const Register = () => import('./views/Register.vue');
const Tasks = () => import('./views/Tasks.vue');
const Notifications = () => import('./views/Notifications.vue');

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/login', name: 'login', component: Login, meta: { guest: true } },
    { path: '/register', name: 'register', component: Register, meta: { guest: true } },
    { path: '/', redirect: '/tasks' },
    { path: '/tasks', name: 'tasks', component: Tasks, meta: { requiresAuth: true } },
    { path: '/notifications', name: 'notifications', component: Notifications, meta: { requiresAuth: true } },
  ],
});

router.beforeEach(async (to) => {
  const auth = useAuthStore();
  if (auth.isAuthenticated && !auth.user) {
    try { await auth.fetchMe(); } catch { await auth.logout(); }
  }
  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return { name: 'login', query: { redirect: to.fullPath } };
  }
  if (to.meta.guest && auth.isAuthenticated) {
    return { name: 'tasks' };
  }
});

export default router;


