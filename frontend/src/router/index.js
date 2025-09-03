import { createWebHistory, createRouter } from 'vue-router'

// Auth
import adminLogin from '@/pages/auth/loginPage.vue'

// Layouts
import AdminLayout from '@/layouts/AdminLayout.vue'

// Pages
import adminDashboard from '@/pages/dashboard/admin/adminDashboard.vue'
import NotFound from '@/pages/NotFound.vue'
import adminUpdate from '@/pages/auth/adminUpdate.vue'

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: adminLogin
  },

  // Dashboard Root
  {
    path: '/dashboard/admin',
    component: AdminLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'Home',
        component: adminDashboard
      },
      {
        path: 'update',
        name: 'AdminUpdate',
        component: adminUpdate
      },
      {
        path: 'inventory/item-types',
        name: 'ItemTypes',
        component: () => import('@/pages/dashboard/admin/inventory/ItemTypes.vue')
      },
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
