<template>
    <v-main>
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="mx-4 my-4">
            <p class="text-h6 my-4 ml-2">USUARIOS</p>
            <v-btn class="mr-4 mt-4 new_btn txt_white bk_green" large :to='{ name: "newUser" }'>
                <v-icon class="mr-2">person_add</v-icon>
                Nuevo
            </v-btn>
            <!-- Tabla -->
            <v-card class="mx-auto mt-10 px-5 py-3" elevation="0">
                <v-text-field v-model="search" class="mb-1" prepend-icon="search" label="Buscar" tabindex="1">
                </v-text-field>
                <v-data-table :headers="headers" :items="data" :items-per-page="10" :footer-props="{
                    showFirstLastPage: true,
                    firstIcon: 'first_page',
                    lastIcon: 'last_page',
                    prevIcon: 'chevron_left',
                    nextIcon: 'chevron_right'
                }" :loading="loading_table" loading-text="Obteniendo información..." multi-sort
                    :search="search" fixed-header align="center">
                    <!-- Avatar -->
                    <template v-slot:item.avatar="{ item }">
                        <template v-if="item.avatar">
                            <v-list-item-avatar class="mx-auto">
                                <v-img :src='"/img/users/" + item.avatar' :max-height='avatar_height'
                                    :lazy-src='"/img/lazy_users/" + item.avatar'>
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
                            <v-icon>account_circle</v-icon>
                        </template>
                    </template>
                    <!-- Estado -->
                    <template v-slot:item.status="{ item }">
                        <div>
                            <template v-if="item.status == 0">
                                <template v-if="user.id !== item.id">
                                    <v-btn icon @click.prevent="statusUser(item.id, 1)">
                                        <v-icon>
                                            check_box_outline_blank
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <template v-else>
                                    <v-icon>check_box_outline_blank</v-icon>
                                </template>
                            </template>
                            <template v-else-if="item.status == 1">
                                <template v-if="user.id !== item.id">
                                    <v-btn icon @click.prevent="statusUser(item.id, 0)">
                                        <v-icon>
                                            check_box
                                        </v-icon>
                                    </v-btn>
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
                        <template v-if="user.id !== item.id">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon class="txt_blue" v-bind="attrs" v-on="on"
                                        :to='{ name: "editUser", params: { id: item.id } }'>
                                        <v-icon>
                                            settings
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>Ver usuario</span>
                            </v-tooltip>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon class="txt_red" @click.prevent="deleteUser(item.id)" v-bind="attrs"
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
                </v-data-table>
            </v-card>
        </div>
    </v-main>
</template>

<script>
export default {
    name: "HomeUser",
    data: () => ({
        overlay: false,
        sweet: {
            icon: "error",
            title: "Error",
        },
        loading_table: true,
        headers: [
            { text: 'Avatar', value: 'avatar', align: 'center', sortable: false },
            { text: 'Nombres', value: 'firstname', align: 'center' },
            { text: 'Apellidos', value: 'lastname', align: 'center' },
            { text: 'Correo electrónico', value: 'email', align: 'center' },
            { text: 'Usuario', value: 'user', align: 'center' },
            { text: 'Estado', value: 'status', align: 'center' },
            { text: 'Acciones', value: 'actions', align: 'center', sortable: false },
        ],
        avatar_height: 40,
        search: '',
        data: [],
        user: {
            id: "",
            user: "",
        },
    }),
    mounted() {
        this.login();
        this.allUsers();
    },
    methods: {
        async allUsers() {
            await this.axios.get('/api/user')
                .then(response => {
                    this.data = response.data;
                    this.loading_table = false;
                })
                .catch(error => {
                    this.data = []
                })
        },
        async login() {
            await this.axios.get('/api/auth')
                .then(response => {
                    this.user = response.data;
                }).catch((error) => {
                    console.log(error);
                });
        },
        async deleteUser(item) {
            await this.$swal({
                title: '¿Esta seguro de eliminar el usuario?',
                text: "Esta acción no se puede revertir",
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
                                if (response.data.complete) {
                                    this.sweet.title = "Éxito"
                                    this.sweet.icon = "success";
                                }
                                else {
                                    this.sweet.title = "Error"
                                    this.sweet.icon = "error";
                                }
                                this.$swal({
                                    title: this.sweet.title,
                                    icon: this.sweet.icon,
                                    text: response.data.message,
                                });
                                this.login();
                                this.allUsers();
                                this.overlay = false;
                            })
                            .catch(error => {
                                this.sweet.title = "Error"
                                this.sweet.icon = "error";
                                this.$swal({
                                    title: this.sweet.title,
                                    icon: this.sweet.icon,
                                    text: error,
                                });
                                this.login();
                                this.allUsers();
                                this.overlay = false;
                            });
                    }
                });
        },
        async statusUser(item, type) {
            await this.$swal({
                title: '¿Esta seguro de cambiar el estado del usuario?',
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
                                if (response.data.complete) {
                                    this.sweet.title = "Éxito"
                                    this.sweet.icon = "success";
                                }
                                else {
                                    this.sweet.title = "Error"
                                    this.sweet.icon = "error";
                                }
                                this.$swal({
                                    title: this.sweet.title,
                                    icon: this.sweet.icon,
                                    text: response.data.message,
                                });
                                this.login();
                                this.allUsers();
                                this.overlay = false;
                            })
                            .catch(error => {
                                this.sweet.title = "Error"
                                this.sweet.icon = "error";
                                this.$swal({
                                    title: this.sweet.title,
                                    icon: this.sweet.icon,
                                    text: error,
                                });
                                this.login();
                                this.allUsers();
                                this.overlay = false;
                            });
                    }
                });
        }
    },
}
</script>