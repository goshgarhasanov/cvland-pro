import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'

import { useAuthStore } from '@/stores/auth'

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: () => import('@/layouts/AppLayout.vue'),
    children: [
      { path: '', name: 'home', component: () => import('@/pages/HomePage.vue') },
      {
        path: 'dashboard',
        name: 'dashboard',
        component: () => import('@/pages/DashboardPage.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'cvs',
        name: 'cvs.index',
        component: () => import('@/pages/CvListPage.vue'),
        meta: { requiresAuth: true },
      },
      {
        path: 'templates',
        name: 'templates.index',
        component: () => import('@/pages/TemplatesPage.vue'),
        meta: { requiresAuth: true },
      },
    ],
  },
  {
    path: '/auth',
    component: () => import('@/layouts/AuthLayout.vue'),
    children: [
      { path: 'login', name: 'auth.login', component: () => import('@/pages/LoginPage.vue') },
      {
        path: 'register',
        name: 'auth.register',
        component: () => import('@/pages/RegisterPage.vue'),
      },
    ],
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: () => import('@/pages/NotFoundPage.vue'),
  },
]

export const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior() {
    return { top: 0 }
  },
})

router.beforeEach(async (to) => {
  const auth = useAuthStore()
  if (!auth.initialised) {
    await auth.initialise()
  }

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return { name: 'auth.login', query: { redirect: to.fullPath } }
  }

  if (to.path.startsWith('/auth/') && auth.isAuthenticated) {
    return { name: 'dashboard' }
  }

  return true
})

window.addEventListener('auth:unauthenticated', () => {
  void router.push({ name: 'auth.login' })
})
