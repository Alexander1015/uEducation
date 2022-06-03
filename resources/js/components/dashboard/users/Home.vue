<template>
    <v-main>
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Snackbar -->
        <v-snackbar v-model="snackbar.active" :color="snackbar.color" :timeout="snackbar.timeout" top elevation="24">
            {{ snackbar.text }}
            <template v-slot:action="{ attrs }">
                <v-btn class="txt_white" icon v-bind="attrs" @click="snackbar.active = false">
                    <v-icon>close</v-icon>
                </v-btn>
            </template>
        </v-snackbar>
        <!-- Contenido -->
        <div class="mx-2 my-2">
            <p class="text-h5 my-4 ml-2">Usuarios / Docentes</p>
            <v-btn class="mr-10 my-2 new_btn txt_white" large color="teal" :to='{ name: "newUsers" }'>
                <v-icon class="mr-2">person_add</v-icon>
                Nuevo
            </v-btn>
            <!-- Tabla -->
            <v-card class="mx-auto rounded mt-2 px-5 py-3" elevation="1">
                <v-text-field v-model="search" class="mb-1" prepend-icon="search" label="Buscar" tabindex="1">
                </v-text-field>
                <v-data-table :headers="headers" :items="data" :items-per-page="10" :footer-props="{
                    showFirstLastPage: true,
                    firstIcon: 'first_page',
                    lastIcon: 'last_page',
                    prevIcon: 'chevron_left',
                    nextIcon: 'chevron_right'
                }" :loading="loading_table" loading-text="Obteniendo información... Por favor espera" multi-sort
                    :search="search">
                    <template v-slot:item.avatar="{ item }">
                        <template v-if="item.avatar">
                            <v-list-item-avatar class="mx-auto">
                                <v-img :src='"/img/users/" + item.avatar' :max-height='avatar_height'
                                    :lazy-src='"/img/lazy/users/" + item.avatar'>
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
                    <template v-slot:item.actions="{ item }">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon small class="mr-2 txt_blue" @click="editUser(item)" v-bind="attrs" v-on="on">
                                    edit
                                </v-icon>
                            </template>
                            <span>Modificar</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon small class="mr-2 txt_red" @click="deleteUser(item.id)" v-bind="attrs" v-on="on">
                                    delete
                                </v-icon>
                            </template>
                            <span>Eliminar</span>
                        </v-tooltip>
                    </template>
                </v-data-table>
            </v-card>
            <!-- Models -->
            <router-view></router-view>
        </div>
    </v-main>
</template>

<script>
export default {
    name: "HomeUser",
    data: () => ({
        overlay: false,
        snackbar: {
            active: false,
            text: "Mensaje",
            timeout: 2000,
            color: ""
        },
        loading_table: true,
        headers: [
            { text: 'Nombres', value: 'firstname' },
            { text: 'Apellidos', value: 'lastname' },
            { text: 'Correo Electrónico', value: 'email' },
            { text: 'Usuario', value: 'user' },
            { text: 'Avatar', value: 'avatar' },
            { text: 'Acciones', value: 'actions', sortable: false },
        ],
        avatar_height: 40,
        search: '',
        data: [],
    }),
    mounted() {
        this.allUsers()
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
        editUser(item) {
            // Aqui va codigo para actualizar un Usuario
        },
        deleteUser(item) {
            if (confirm("¿Esta seguro de eliminar el registro?, esta acción no se puede revertir")) {
                this.overlay = true;
                this.axios.delete('/api/user/' + item)
                    .then(response => {
                       if (response.data.complete) {
                            this.snackbar.color = "green darken-1";
                            // Borramos los datos de los input
                            this.$refs.form.reset();
                        }
                        this.snackbar.text = response.data.message;
                        this.snackbar.active = true;
                        this.overlay = false;
                        this.allUsers()
                    })
                    .catch(error => {
                        this.snackbar.color = "red darken-1"
                        this.snackbar.text = error;
                        this.overlay = false;
                        this.snackbar.active = true;
                    })
            }
        }
    },
}
</script>