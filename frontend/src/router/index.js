import { createWebHistory, createRouter } from 'vue-router'
import adminLogin from '@/pages/admin/auth/adminLogin.vue'
import adminDashboard from '@/pages/admin/dashboard/adminDashboard.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import NotFound from '@/pages/NotFound.vue'

const routes = [
  {
    path: '/admin/login',
    name: 'Login',
    component: adminLogin
  },
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: 'dashboard',
        name: 'Home',
        component: adminDashboard
      },
      // {
      //   path: 'inventory/item-types',
      //   name: 'ItemTypes',
      //   component: () => import('@/pages/admin/inventory/ItemTypes.vue')
      // },
      // {
      //   path: 'inventory/item-categories',
      //   name: 'ItemCategories',
      //   component: () => import('@/pages/admin/inventory/ItemCategories.vue')
      // },
      // tambah route admin lain di sini
    ]
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: NotFound,
    props: true
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('authToken')

  if (to.meta.requiresAuth && !isAuthenticated) {
    next({ name: 'Login' })
  } else if (to.name === 'Login' && isAuthenticated) {
    next({ name: 'Home' })
  } else {
    next()
  }
})

export default router
