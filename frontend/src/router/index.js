import { createWebHistory, createRouter } from 'vue-router'

// Auth
import adminLogin from '@/pages/auth/loginPage.vue'

// Layouts
import AdminLayout from '@/layouts/AdminLayout.vue'

// Pages
// import adminDashboard from '@/pages/dashboard/admin/adminDashboard.vue'
import NotFound from '@/pages/NotFound.vue'
import adminUpdate from '@/pages/auth/adminUpdate.vue'
// import userTable from '@/pages/dashboard/admin/operasi/UserTable.vue'
import DataBarang from '@/pages/inventory/DataBarang.vue'
import KategoriBarang from '@/pages/inventory/KategoriBarang.vue'
import PeminjamanBarang from '@/pages/inventory/PeminjamanBarang.vue'
import Formpeminjaman from '@/pages/inventory/Formpeminjaman.vue'

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: adminLogin
  },
  {
    path: '/',
    name: 'LandingPage',
    component: () => import('@/layouts/LandingPageLayout.vue')
  },

  // Dashboard Root
  {
    path: '/dashboard/admin',
    component: AdminLayout,
    meta: { requiresAuth: true, role: 'admin' },
    redirect: { name: 'Home' }, // ⬅️ Redirect default ke dashboard
    children: [
      {
        path: '',
        name: 'Home',
        component: () => import('@/pages/dashboard/admin/adminDashboard.vue')
      },
      {
        path: 'update',
        name: 'AdminUpdate',
        component: adminUpdate
      },
      {
        path: 'inventory',
        children: [
          {
            path: 'items',
            name: 'Items',
            component: () => import('@/pages/dashboard/admin/inventory/ItemsPage.vue')
          },
          {
            path: 'item-types',
            name: 'ItemTypes',
            component: () => import('@/pages/dashboard/admin/inventory/ItemTypes.vue')
          },
          {
            path: 'data-barang',
            name: 'DataBarang',
            component: DataBarang
          },
          {
            path: 'kategori-barang',
            name: 'KategoriBarang',
            component: KategoriBarang
          },
          {
            path: 'peminjaman-barang',
            name: 'PeminjamanBarang',
            component: PeminjamanBarang
          },
          {
            path: 'form-peminjaman',
            name: 'Formpeminjaman',
            component: Formpeminjaman
          }
        ]
      },
      {
        path: 'service',
        children: [
          {
            path: 'list',
            name: 'ServiceList',
            component: () => import('@/pages/dashboard/admin/service/ServiceList.vue')
          }
        ]
      },
      {
        path: 'operasi',
        children: [
          {
            path: 'user-management',
            name: 'UserManagement',
            component: () => import('@/pages/dashboard/admin/operasi/UserManagement.vue')
          },
          {
            path: 'user-table',
            name: 'UserTable',
            component: () => import('@/pages/dashboard/admin/operasi/UserTable.vue')
          }
        ]
      }
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
  const token = localStorage.getItem('auth_token')

  console.log('Navigating to:', to.fullPath)
  console.log('Token in guard:', token)

  //  Cek level route
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth)

  // login tapi gada token
  if (requiresAuth && !token) {
    console.warn('⛔ Akses ditolak: belum login')
    return next({ name: 'Login' })
  }

  // udh login, login lagi
  if (to.name === 'Login' && token) {
    console.info('Sudah login, alihkan ke dashboard')
    return next('/dashboard/admin')
  }

  // masuk ke dashboard
  console.log('✅ Akses diizinkan')
  next()
})

export default router
