<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="mx-2 mt-1 mb-4 mx-auto px-5 py-3">
            <div class="mb-4">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn class="mt-n1" v-bind="attrs" v-on="on" icon @click.stop="allUsers()">
                            <v-icon>autorenew</v-icon>
                        </v-btn>
                    </template>
                    <span>Actualizar</span>
                </v-tooltip>
                <span class="text-h6">DOCENTES</span>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn class="ml-4 mt-n2 bk_green txt_white" v-bind="attrs" v-on="on" @click.stop="gotoNew()">
                            <v-icon>add_box</v-icon>
                        </v-btn>
                    </template>
                    <span>Nuevo</span>
                </v-tooltip>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn class="ml-4 mt-n2 bk_blue txt_white" v-bind="attrs" v-on="on" @click.stop="gotoLoad()">
                            <v-icon>upload_file</v-icon>
                        </v-btn>
                    </template>
                    <span>Cargar información de nuevos docentes</span>
                </v-tooltip>
            </div>
            <div class="mb-8">
                <p class="text-justify">Listado de los docentes existentes en la aplicación</p>
            </div>
            <v-text-field v-model="search" class="ml-2 mb-1" prepend-icon="search" label="Buscar" dense>
            </v-text-field>
            <!-- Tabla -->
            <v-data-table :headers="headers" :items="data" :items-per-page="10" :footer-props="{
                showFirstLastPage: true,
                firstIcon: 'first_page',
                lastIcon: 'last_page',
                prevIcon: 'chevron_left',
                nextIcon: 'chevron_right'
            }" :loading="loading_table" loading-text="Obteniendo información"
                no-data-text="No se ha obtenido información" no-results-text="No se obtuvieron resultados" multi-sort
                :search="search" fixed-header align="center">
                <!-- Avatar -->
                <template v-slot:item.avatar="{ item }">
                    <template v-if="item.avatar">
                        <v-list-item-avatar class="mx-auto">
                            <v-img :src='"/img/users/" + item.avatar + "/index.png"' max-height='38' max-width="38"
                                :lazy-src='"/img/users/" + item.avatar + "/lazy.png"'>
                                <template v-slot:placeholder>
                                    <v-row class="fill-height ma-0" align="center" justify="center">
                                        <v-progress-circular indeterminate color="grey lighten-5">
                                        </v-progress-circular>
                                    </v-row>
                                </template>
                            </v-img>
                        </v-list-item-avatar>
                    </template>
                    <template v-else>
                        <v-list-item-avatar class="mx-auto">
                            <v-img src="/img/users/blank.png" max-height="38" max-width="38"
                                lazy-src="/img/users/blank_lazy.png">
                                <template v-slot:placeholder>
                                    <v-row class="fill-height ma-0" align="center" justify="center">
                                        <v-progress-circular indeterminate color="grey lighten-5">
                                        </v-progress-circular>
                                    </v-row>
                                </template>
                            </v-img>
                        </v-list-item-avatar>
                    </template>
                </template>
                <!-- Tipo -->
                <template v-slot:item.type="{ item }">
                    <div>
                        <template v-if="item.type == '0'">
                            Administrador
                        </template>
                        <template v-else-if="item.type == '1'">
                            Docente
                        </template>
                        <template v-else>
                            <v-icon>remove</v-icon>
                        </template>
                    </div>
                </template>
                <!-- Estado -->
                <template v-slot:item.status="{ item }">
                    <div>
                        <template v-if="item.status == 0">
                            <template v-if="user.slug !== item.slug">
                                <template v-if="user.type == '0'">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn icon v-bind="attrs" v-on="on" @click.stop="statusUser(item.slug, 1)">
                                                <v-icon>
                                                    check_box_outline_blank
                                                </v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Habilitar</span>
                                    </v-tooltip>
                                </template>
                                <template v-else>
                                    <v-icon>check_box_outline_blank</v-icon>
                                </template>
                            </template>
                            <template v-else>
                                <v-icon>check_box_outline_blank</v-icon>
                            </template>
                        </template>
                        <template v-else-if="item.status == 1">
                            <template v-if="user.slug !== item.slug">
                                <template v-if="user.type == '0'">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn icon v-bind="attrs" v-on="on" @click.stop="statusUser(item.slug, 0)">
                                                <v-icon>
                                                    check_box
                                                </v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Deshabilitar</span>
                                    </v-tooltip>
                                </template>
                                <template v-else>
                                    <v-icon>check_box</v-icon>
                                </template>
                            </template>
                            <template v-else>
                                <v-icon>check_box</v-icon>
                            </template>
                        </template>
                        <template v-else>
                            <v-icon>indeterminate_check_box</v-icon>
                        </template>
                    </div>
                </template>
                <template v-slot:item.actions="{ item }">
                    <template v-if="user.slug !== item.slug">
                        <template v-if="user.type == '0' || (user.type == '1' && item.type == '1')">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon class="txt_blue" v-bind="attrs" v-on="on"
                                        @click.stop="gotoEdit(item.slug)">
                                        <v-icon>
                                            settings
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>Ver usuario</span>
                            </v-tooltip>
                        </template>
                        <template v-if="user.type == '0'">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon class="txt_red" @click.stop="deleteUser(item.slug)" v-bind="attrs"
                                        v-on="on">
                                        <v-icon>
                                            delete
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>Eliminar</span>
                            </v-tooltip>
                        </template>
                        <template v-else>
                            <v-icon>remove</v-icon>
                        </template>
                    </template>
                    <template v-else>
                        <v-icon>remove</v-icon>
                    </template>
                </template>
            </v-data-table>
        </div>
    </v-main>
</template>

<script>
export default {
    name: "HomeUser",
    data: () => ({
        overlay: false,
        loading_table: false,
        headers: [
            { text: 'Avatar', value: 'avatar', align: 'center', sortable: false },
            { text: 'Nombres', value: 'firstname', align: 'center' },
            { text: 'Apellidos', value: 'lastname', align: 'center' },
            { text: 'Correo electrónico', value: 'email', align: 'center' },
            { text: 'Usuario', value: 'user', align: 'center' },
            { text: 'Tipo', value: 'type', align: 'center' },
            { text: 'Estado', value: 'status', align: 'center' },
            { text: 'Acciones', value: 'actions', align: 'center', sortable: false },
        ],
        search: '',
        data: [],
        user: {
            id: "",
            user: "",
        },
    }),
    mounted() {
        this.allUsers();
    },
    methods: {
        gotoEdit(item) {
            this.overlay = true;
            this.$router.push({ name: "editUser", params: { slug: item } });
        },
        gotoNew() {
            this.overlay = true;
            this.$router.push({ name: "newUser" });
        },
        gotoLoad() {
            this.overlay = true;
            this.$router.push({ name: "loadUsers" });
        },
        async allUsers() {
            this.overlay = true;
            this.loading_table = true;
            this.data = [];
            await this.axios.get('/api/auth')
                .then(response => {
                    this.user = response.data;
                    if (this.user.type != "0") {
                        this.$router.push({ name: "forbiden" });
                    }
                }).catch((error) => {
                    console.log(error);
                    this.axios.post('/api/logout')
                        .then(response => {
                            window.location.href = "/auth"
                        }).catch((error) => {
                            console.log(error);
                        });
                });
            await this.axios.get('/api/user')
                .then(response => {
                    this.data = response.data;
                    this.loading_table = false;
                    this.overlay = false;
                })
                .catch(error => {
                    console.log(error);
                    this.data = [];
                    this.loading_table = false;
                    this.overlay = false;
                });
        },
        async deleteUser(item) {
            await this.$swal({
                title: '¿Está seguro de eliminar el usuario?',
                text: "Está acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            })
                .then(result => {
                    if (result.isConfirmed) {
                        this.overlay = true;
                        this.axios.delete('/api/user/' + item)
                            .then(response => {
                                let title = "Error";
                                let icon = "error";
                                if (response.data.complete) {
                                    title = "Éxito"
                                    icon = "success";
                                }
                                this.$swal({
                                    title: title,
                                    icon: icon,
                                    text: response.data.message,
                                }).then(() => {
                                    this.overlay = false;
                                    this.allUsers();
                                });
                            })
                            .catch(error => {
                                this.$swal({
                                    title: "Error",
                                    icon: "error",
                                    text: "Ha ocurrido un error en la aplicación",
                                }).then(() => {
                                    console.log(error);
                                    this.overlay = false;
                                    this.allUsers();
                                });
                            });
                    }
                });
        },
        async statusUser(item, type) {
            await this.$swal({
                title: '¿Está seguro de ' + (type == 1 ? "habilitar" : (type == 0 ? "deshabilitar" : "cambiar el estado de")) + ' el usuario seleccionado?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            })
                .then(result => {
                    if (result.isConfirmed) {
                        this.overlay = true;
                        let data = new FormData();
                        data.append('status', type);
                        data.append('_method', "put");
                        this.axios.post('/api/user/status/' + item, data)
                            .then(response => {
                                let title = "Error";
                                let icon = "error";
                                if (response.data.complete) {
                                    title = "Éxito"
                                    icon = "success";
                                }
                                this.$swal({
                                    title: title,
                                    icon: icon,
                                    text: response.data.message,
                                }).then(() => {
                                    this.overlay = false;
                                    this.allUsers();
                                });
                            })
                            .catch(error => {
                                this.$swal({
                                    title: "Error",
                                    icon: "error",
                                    text: "Ha ocurrido un error en la aplicación",
                                }).then(() => {
                                    console.log(error);
                                    this.overlay = false;
                                    this.allUsers();
                                });
                            });
                    }
                });
        }
    },
}
</script>