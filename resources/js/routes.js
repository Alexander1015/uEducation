import axios from 'axios'

const Public = () => import ('./components/public/Home.vue')
const PublicBlogs = () => import ('./components/public/blogs/Home.vue')
const Auth = () => import ('./components/auth/Home.vue')
const Register = () => import ('./components/auth/Register.vue')
const DashboardUsers = () => import ('./components/dashboard/users/Home.vue')

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
        name: 'register',
        path: '/auth/register',
        component: Register
    },
    {
        name: 'blogs',
        path: '/blogs',
        component: PublicBlogs
    }, {
        name: 'users',
        path: '/dashboard/users',
        component: DashboardUsers,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                next()
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    },
]
