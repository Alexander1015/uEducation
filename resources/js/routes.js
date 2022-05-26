const Public = () => import('./components/public/Home.vue')
const Auth = () => import('./components/auth/Home.vue')
const Dashboard = () => import('./components/dashboard/Home.vue')

export const routes = [
    {
        name: 'public',
        path: '/',
        component: Public 
    },
    {
        name: 'auth',
        path: '/auth',
        component: Auth
    },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: Dashboard
    },

]