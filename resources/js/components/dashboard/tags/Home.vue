<template>
    <v-main>
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="mx-2 my-2">
            <p class="text-h5 my-4 ml-2">Etiquetas / Tags</p>
            <v-btn class="mr-10 my-2 new_btn txt_white bk_green" large :to='{ name: "newTag" }'>
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
                    :search="search" fixed-header>
                    <template v-slot:item.status="{ item }">
                        <v-switch color="success" v-model="item.status" inset disabled></v-switch>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon class="mr-2 txt_green" @click="editTag(item.id)" v-bind="attrs" v-on="on">
                                    edit
                                </v-icon>
                            </template>
                            <span>Modificar</span>
                        </v-tooltip>
                        <template v-if="item.status == 0">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-icon class="mr-2 txt_green" @click="statusTag(item.id, 1)" v-bind="attrs"
                                        v-on="on">
                                        check_circle
                                    </v-icon>
                                </template>
                                <span>Activar etiqueta</span>
                            </v-tooltip>
                        </template>
                        <template v-else-if="item.status == 1">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-icon class="mr-2 txt_red" @click="statusTag(item.id, 0)" v-bind="attrs"
                                        v-on="on">
                                        cancel
                                    </v-icon>
                                </template>
                                <span>Desactivar etiqueta</span>
                            </v-tooltip>
                        </template>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-icon class="mr-2 txt_red" @click="deleteTag(item.id)" v-bind="attrs" v-on="on">
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
    name: "HomeTags",
    data: () => ({
        overlay: false,
        sweet: {
            icon: "error",
            title: "Error",
        },
        loading_table: true,
        headers: [
            { text: 'Titulo', value: 'name', align: 'center' },
            { text: 'Estado', value: 'status', align: 'center' },
            { text: 'Acciones', value: 'actions', align: 'center', sortable: false },
        ],
        search: '',
        data: [],
    }),
    mounted() {
        this.allTags();
    },
    methods: {
        async allTags() {
            await this.axios.get('/api/tag')
                .then(response => {
                    this.data = response.data;
                    this.loading_table = false;
                })
                .catch(error => {
                    this.data = []
                })
        },
        editTag(item) {
            this.$router.push({ name: "editTag", params: { id: item } });
        },
        async deleteTag(item) {
            await this.$swal({
                title: '¿Esta seguro de eliminar la etiqueta?',
                text: "Esta acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            })
                .then(result => {
                    if (result.isConfirmed) {
                        this.overlay = true;
                        this.axios.delete('/api/tag/' + item)
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
                                this.overlay = false;
                                this.allTags()
                            })
                            .catch(error => {
                                this.sweet.title = "Error"
                                this.sweet.icon = "error";
                                this.$swal({
                                    title: this.sweet.title,
                                    icon: this.sweet.icon,
                                    text: error,
                                });
                                this.overlay = false;
                            });
                    }
                });
        },
        async statusTag(item, type) {
            await this.$swal({
                title: '¿Esta seguro de cambiar el estado de la etiqueta?',
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
                        this.axios.post('/api/tag/status/' + item, data)
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
                                this.overlay = false;
                                this.allTags()
                            })
                            .catch(error => {
                                this.sweet.title = "Error"
                                this.sweet.icon = "error";
                                this.$swal({
                                    title: this.sweet.title,
                                    icon: this.sweet.icon,
                                    text: error,
                                });
                                this.overlay = false;
                            });
                    }
                });
        }
    },
}
</script>