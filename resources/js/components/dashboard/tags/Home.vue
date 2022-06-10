<template>
    <v-main>
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="mx-4 my-4">
            <p class="text-h6 my-4 ml-2">ETIQUETAS</p>
            <v-btn class="mr-4 mt-4 new_btn txt_white bk_green" large :to='{ name: "newTag" }'>
                <v-icon class="mr-2">bookmark_add</v-icon>
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
                }" :loading="loading_table" loading-text="Obteniendo información... Por favor espera" multi-sort
                    :search="search" fixed-header>
                    <template v-slot:item.status="{ item }">
                        <div>
                            <template v-if="item.status == 0">
                                <v-btn icon @click.prevent="statusTag(item.id, 1)">
                                    <v-icon>
                                        check_box_outline_blank
                                    </v-icon>
                                </v-btn>
                            </template>
                            <template v-else-if="item.status == 1">
                                <v-btn icon @click.prevent="statusTag(item.id, 0)">
                                    <v-icon>
                                        check_box
                                    </v-icon>
                                </v-btn>
                            </template>
                            <template v-else>
                                <v-icon>indeterminate_check_box</v-icon>
                            </template>
                        </div>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn icon class="txt_blue" v-bind="attrs" v-on="on"
                                    :to='{ name: "editTag", params: { id: item.id } }'>
                                    <v-icon>
                                        settings
                                    </v-icon>
                                </v-btn>
                            </template>
                            <span>Ver curso</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn icon class="txt_red" @click.prevent="deleteTag(item.id)" v-bind="attrs"
                                    v-on="on">
                                    <v-icon>
                                        delete
                                    </v-icon>
                                </v-btn>
                            </template>
                            <span>Eliminar</span>
                        </v-tooltip>
                    </template>
                </v-data-table>
            </v-card>
        </div>
    </v-main>
</template>

<script>
export default {
    name: "HomeTag",
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
                                this.allTags()
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
                                this.allTags()
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
                                this.allTags()
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
                                this.overlay = false;
                            });
                    }
                });
        }
    },
}
</script>