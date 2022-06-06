import axios from 'axios'

const Public = () => import ('./components/public/Home.vue')
const PublicBlogs = () => import ('./components/public/blogs/Home.vue')
const Auth = () => import ('./components/auth/Home.vue')
const Register = () => import ('./components/auth/Register.vue')
const DashboardUsers = () => import ('./components/dashboard/users/Home.vue')
const NewUser = () => import ('./components/dashboard/users/NewUser.vue')
const EditUser = () => import ('./components/dashboard/users/EditUser.vue')
const PasswordUser = () => import ('./components/dashboard/users/PasswordUser.vue')
const Profile = () => import ('./components/dashboard/profile/Home.vue')

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
        children: [
            {
                name: 'newUser',
                path: '/dashboard/users/new',
                component: NewUser
            }, {
                name: 'editUser',
                path: '/dashboard/users/edit',
                component: EditUser
            }, {
                name: 'passwordUser',
                path: '/dashboard/users/password',
                component: PasswordUser
            },
        ],
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                next()
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'profile',
        path: '/dashboard/profile',
        component: Profile,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                next()
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }
]
