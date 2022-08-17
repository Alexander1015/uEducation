import axios from 'axios'

// Public
const Public = () => import ('./components/public/Home.vue')
const PublicContents = () => import ('./components/public/Contents.vue')
const PublicSubject = () => import ('./components/public/Subject.vue')
const PublicTopic = () => import ('./components/public/Topic.vue')
// Auth
const Auth = () => import ('./components/auth/Home.vue')
// Carousel
const DashboardCarousel = () => import ('./components/dashboard/carousel/Home.vue')
// Users
const DashboardUsers = () => import ('./components/dashboard/users/Home.vue')
const NewUser = () => import ('./components/dashboard/users/NewUser.vue')
const EditUser = () => import ('./components/dashboard/users/EditUser.vue')
// Students
const DashboardStudents = () => import ('./components/dashboard/students/Home.vue')
const NewStudent = () => import ('./components/dashboard/students/NewStudent.vue')
const EditStudent = () => import ('./components/dashboard/students/EditStudent.vue')
// Subjects
const DashboardSubjects = () => import ('./components/dashboard/subjects/Home.vue')
const NewSubject = () => import ('./components/dashboard/subjects/NewSubject.vue')
const EditSubject = () => import ('./components/dashboard/subjects/EditSubject.vue')
// Tags
const DashboardTags = () => import ('./components/dashboard/tags/Home.vue')
const NewTag = () => import ('./components/dashboard/tags/NewTag.vue')
const EditTag = () => import ('./components/dashboard/tags/EditTag.vue')
// Topics
const DashboardTopics = () => import ('./components/dashboard/topics/Home.vue')
const NewTopic = () => import ('./components/dashboard/topics/NewTopic.vue')
const EditTopic = () => import ('./components/dashboard/topics/EditTopic.vue')
// Profile
const Profile = () => import ('./components/dashboard/profile/Home.vue')
// Error 404
const Error = () => import ('./components/error/Home.vue')


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
        name: 'contents',
        path: '/contents',
        component: PublicContents,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                next()
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    },
    {
        name: 'publicSubject',
        path: '/contents/:subject',
        component: PublicSubject,
        children: [
            {
                name: 'publicTopic',
                path: ':topic',
                component: PublicTopic
            }
        ],
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                next()
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'carousel',
        path: '/carousel',
        component: DashboardCarousel,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "2") {
                        return next({name: 'error'})
                    } else {
                        next()
                    }
                }).catch((error) => {
                    return next({name: 'error'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'users',
        path: '/dashboard/users',
        component: DashboardUsers,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "2") {
                        return next({name: 'error'})
                    } else {
                        next()
                    }
                }).catch((error) => {
                    return next({name: 'error'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'newUser',
        path: '/dashboard/users/new',
        component: NewUser,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "2") {
                        return next({name: 'error'})
                    } else {
                        next()
                    }
                }).catch((error) => {
                    return next({name: 'error'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'editUser',
        path: '/dashboard/users/edit/:slug',
        component: EditUser,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "2") {
                        return next({name: 'error'})
                    } else {
                        next()
                    }
                }).catch((error) => {
                    return next({name: 'error'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'students',
        path: '/dashboard/students',
        component: DashboardStudents,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "2") {
                        return next({name: 'error'})
                    } else {
                        next()
                    }
                }).catch((error) => {
                    return next({name: 'error'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'newStudent',
        path: '/dashboard/students/new',
        component: NewStudent,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "2") {
                        return next({name: 'error'})
                    } else {
                        next()
                    }
                }).catch((error) => {
                    return next({name: 'error'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'editStudent',
        path: '/dashboard/students/edit/:slug',
        component: EditStudent,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "2") {
                        return next({name: 'error'})
                    } else {
                        next()
                    }
                }).catch((error) => {
                    return next({name: 'error'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'subjects',
        path: '/dashboard/subjects',
        component: DashboardSubjects,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "2") {
                        return next({name: 'error'})
                    } else {
                        next()
                    }
                }).catch((error) => {
                    return next({name: 'error'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'newSubject',
        path: '/dashboard/subjects/new',
        component: NewSubject,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "2") {
                        return next({name: 'error'})
                    } else {
                        next()
                    }
                }).catch((error) => {
                    return next({name: 'error'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'editSubject',
        path: '/dashboard/subjects/edit/:slug',
        component: EditSubject,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "2") {
                        return next({name: 'error'})
                    } else {
                        next()
                    }
                }).catch((error) => {
                    return next({name: 'error'})
                });
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
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "2") {
                        return next({name: 'error'})
                    } else {
                        next()
                    }
                }).catch((error) => {
                    return next({name: 'error'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'newTopic',
        path: '/dashboard/topics/new',
        component: NewTopic,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "2") {
                        return next({name: 'error'})
                    } else {
                        next()
                    }
                }).catch((error) => {
                    return next({name: 'error'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'editTopic',
        path: '/dashboard/topics/edit/:slug',
        component: EditTopic,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "2") {
                        return next({name: 'error'})
                    } else {
                        next()
                    }
                }).catch((error) => {
                    return next({name: 'error'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'tags',
        path: '/dashboard/tags',
        component: DashboardTags,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "2") {
                        return next({name: 'error'})
                    } else {
                        next()
                    }
                }).catch((error) => {
                    return next({name: 'error'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'newTag',
        path: '/dashboard/tags/new',
        component: NewTag,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "2") {
                        return next({name: 'error'})
                    } else {
                        next()
                    }
                }).catch((error) => {
                    return next({name: 'error'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'editTag',
        path: '/dashboard/tags/edit/:slug',
        component: EditTag,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "2") {
                        return next({name: 'error'})
                    } else {
                        next()
                    }
                }).catch((error) => {
                    return next({name: 'error'})
                });
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
    },
    // Error 404
    {
        name: 'error',
        path: '*',
        component: Error
    }
]
