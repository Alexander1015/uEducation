import axios from 'axios'

// Public
const Public = () => import ('./components/public/Home.vue')
const Auth = () => import ('./components/auth/Home.vue')
// Dashboard
// Usuarios
const DashboardUsers = () => import ('./components/dashboard/users/Home.vue')
const NewUser = () => import ('./components/dashboard/users/NewUser.vue')
const EditUser = () => import ('./components/dashboard/users/EditUser.vue')
// Materias
const DashboardSubjects = () => import ('./components/dashboard/subjects/Home.vue')
const NewSubject = () => import ('./components/dashboard/subjects/NewSubject.vue')
const EditSubject = () => import ('./components/dashboard/subjects/EditSubject.vue')
// Tags
const DashboardTags = () => import ('./components/dashboard/tags/Home.vue')
const NewTag = () => import ('./components/dashboard/tags/NewTag.vue')
const EditTag = () => import ('./components/dashboard/tags/EditTag.vue')
// Temas
const DashboardTopics = () => import ('./components/dashboard/topics/Home.vue')
// Perfil
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
    {
        name: 'newUser',
        path: '/dashboard/users/new',
        component: NewUser,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                next()
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'editUser',
        path: '/dashboard/users/edit',
        component: EditUser,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                next()
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'subjects',
        path: '/dashboard/subjects',
        component: DashboardSubjects,
        children: [
            {
                name: 'newSubject',
                path: '/dashboard/subjects/new',
                component: NewSubject
            }, {
                name: 'editSubject',
                path: '/dashboard/subjects/edit',
                component: EditSubject
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
        name: 'tags',
        path: '/dashboard/tags',
        component: DashboardTags,
        children: [
            {
                name: 'newTag',
                path: '/dashboard/tags/new',
                component: NewTag
            }, {
                name: 'editTag',
                path: '/dashboard/tags/edit',
                component: EditTag
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
        name: 'topics',
        path: '/dashboard/topics',
        component: DashboardTopics,
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
