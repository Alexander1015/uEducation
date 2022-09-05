import axios from 'axios'

// Public
const Public = () => import ('./components/public/Home.vue')
// Auth
const Auth = () => import ('./components/auth/Home.vue')
// Contents
const HomeContents = () => import ('./components/dashboard/contents/Home.vue')
const SubjectContents = () => import ('./components/dashboard/contents/Subject.vue')
const TopicContents = () => import ('./components/dashboard/contents/Topic.vue')
// Carousel
const DashboardCarousel = () => import ('./components/dashboard/carousel/Home.vue')
// Users
const DashboardUsers = () => import ('./components/dashboard/users/Home.vue')
const NewUser = () => import ('./components/dashboard/users/NewUser.vue')
const EditUser = () => import ('./components/dashboard/users/EditUser.vue')
const LoadUsers = () => import ('./components/dashboard/users/LoadUsers.vue')
// Students
const DashboardStudents = () => import ('./components/dashboard/students/Home.vue')
const NewStudent = () => import ('./components/dashboard/students/NewStudent.vue')
const EditStudent = () => import ('./components/dashboard/students/EditStudent.vue')
const LoadStudents = () => import ('./components/dashboard/students/LoadStudents.vue')
// Subjects
const DashboardSubjects = () => import ('./components/dashboard/subjects/Home.vue')
const NewSubject = () => import ('./components/dashboard/subjects/NewSubject.vue')
const EditSubject = () => import ('./components/dashboard/subjects/EditSubject.vue')
const LoadSubjects = () => import ('./components/dashboard/subjects/LoadSubjects.vue')
const LoadRelations = () => import ('./components/dashboard/subjects/LoadRelations.vue')
// Tags
const DashboardTags = () => import ('./components/dashboard/tags/Home.vue')
const NewTag = () => import ('./components/dashboard/tags/NewTag.vue')
const EditTag = () => import ('./components/dashboard/tags/EditTag.vue')
const LoadTags = () => import ('./components/dashboard/tags/LoadTags.vue')
// Topics
const DashboardTopics = () => import ('./components/dashboard/topics/Home.vue')
const NewTopic = () => import ('./components/dashboard/topics/NewTopic.vue')
const EditTopic = () => import ('./components/dashboard/topics/EditTopic.vue')
// Recors
const DashboardRecords = () => import ('./components/dashboard/records/Home.vue')
const NewRecord = () => import ('./components/dashboard/records/NewRecord.vue')
// Profile
const Profile = () => import ('./components/dashboard/profile/Home.vue')
// Error
const Error404 = () => import ('./components/error/404.vue')
const Error403 = () => import ('./components/error/403.vue')


export const routes = [
    // Public
    {
        name: 'public',
        path: '/',
        component: Public
    },
    // Login
    {
        name: 'auth',
        path: '/auth',
        component: Auth,
        beforeEnter: (to, from, next) => {
            axios.get('/api/auth').then(response => { // Verificamos si esta logueado el usuario
                let user = response.data;
                if (user.user) {
                    return next({name: 'public'})
                } else {
                    next();
                }
            }).catch((error) => {
                return next({name: 'public'})
            });
        }
    },
    // Contenido
    {
        name: 'contents',
        path: '/contents',
        component: HomeContents,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                next()
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    },
    // Sección de lectura
    {
        name: 'publicSubject',
        path: '/contents/:subject',
        component: SubjectContents,
        children: [
            {
                name: 'publicTopic',
                path: ':topic',
                component: TopicContents
            }
        ],
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                next()
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    },
    // Carrusel
    {
        name: 'carousel',
        path: '/carousel',
        component: DashboardCarousel,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "0" || response.data.type == "1") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    },
    // Usuarios
    {
        name: 'users',
        path: '/dashboard/users',
        component: DashboardUsers,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "0") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
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
                    if (response.data.type == "0") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
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
                    if (response.data.type == "0") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'loadUsers',
        path: '/dashboard/users/load',
        component: LoadUsers,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "0") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    },
    // Estudiantes
    {
        name: 'students',
        path: '/dashboard/students',
        component: DashboardStudents,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "0") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
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
                    if (response.data.type == "0") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
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
                    if (response.data.type == "0") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'loadStudents',
        path: '/dashboard/students/load',
        component: LoadStudents,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "0") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    },
    // Materias
    {
        name: 'subjects',
        path: '/dashboard/subjects',
        component: DashboardSubjects,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "0" || response.data.type == "1") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
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
                    if (response.data.type == "0") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
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
                    if (response.data.type == "0" || response.data.type == "1") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'loadSubjects',
        path: '/dashboard/subjects/load',
        component: LoadSubjects,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "0") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'loadRelations',
        path: '/dashboard/subjects/load/relations',
        component: LoadRelations,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "0") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    },
    // Temas
    {
        name: 'topics',
        path: '/dashboard/topics',
        component: DashboardTopics,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "0" || response.data.type == "1") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
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
                    if (response.data.type == "0" || response.data.type == "1") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
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
                    if (response.data.type == "0" || response.data.type == "1") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    },
    // Etiquetas
    {
        name: 'tags',
        path: '/dashboard/tags',
        component: DashboardTags,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "0" || response.data.type == "1") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
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
                    if (response.data.type == "0" || response.data.type == "1") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
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
                    if (response.data.type == "0" || response.data.type == "1") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'loadTags',
        path: '/dashboard/tags/load',
        component: LoadTags,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "0") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    },
    // Bitacoras
    {
        name: 'records',
        path: '/dashboard/records',
        component: DashboardRecords,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "0") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    }, {
        name: 'newRecord',
        path: '/dashboard/records/new',
        component: NewRecord,
        beforeEnter: (to, from, next) => {
            axios.get('/api/authenticated').then(response => {
                axios.get('/api/auth').then(response => { // Verificamos el tipo de usuario que ingresa
                    if (response.data.type == "0") {
                        next()
                    } else {
                        return next({name: 'forbiden'})
                    }
                }).catch((error) => {
                    return next({name: 'forbiden'})
                });
            }).catch((error) => {
                return next({name: 'auth'})
            });
        }
    },
    // Perfil
    {
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
    // Error 403
    {
        name: 'forbiden',
        path: '/forbiden',
        component: Error403
    },
    // Error 404
    {
        name: 'error',
        path: '*',
        component: Error404
    }
]
