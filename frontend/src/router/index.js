import { createWebHistory, createRouter } from 'vue-router'
import adminLogin from '@/pages/admin/auth/adminLogin.vue'
import adminDashboard from '@/pages/admin/dashboard/adminDashboard.vue'

const routes = [
    { 
        path: '/admin/login', 
        component: adminLogin,
    },
    {
        path: '/admin/dashboard',
        component: adminDashboard,
        meta: {
            requiresAuth: true
        }
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    const isAuthenticated = localStorage.getItem('authToken')

    if (to.meta.requiresAuth && !isAuthenticated) {
        next('/admin/login')
    } else if (to.path === '/admin/login' && isAuthenticated) {
        next('/admin/dashboard')
    } else {
        next();
    }
})

export default router;