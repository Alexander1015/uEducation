import axios from 'axios'

const Public = () => import ('./components/public/Home.vue')
const Auth = () => import ('./components/auth/Home.vue')
const Dashboard = () => import ('./components/dashboard/Home.vue')
const Register = () => import ('./components/auth/Register.vue')

export const routes = [
    {
        name: 'public',
        path: '/',
        component: Public
    }, {
        name: 'auth',
        path: '/auth',
        component: Auth
    }, {
        name: 'register',
        path: '/auth/register',
        component: Register
    }, {
        name: 'dashboard',
        path: '/dashboard',
        component: Dashboard,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                next()
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    },
]
