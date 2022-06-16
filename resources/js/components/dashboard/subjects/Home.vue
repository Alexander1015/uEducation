<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="ma-2">
            <p class="text-h6 my-4 ml-2">CURSOS</p>
            <v-btn class="mr-4 mt-4 new_btn txt_white bk_green" large :to='{ name: "newSubject" }'>
                <v-icon left>post_add</v-icon>
                Nuevo
            </v-btn>
            <!-- Tabla -->
            <v-card class="mx-auto mt-4 px-5 py-3" elevation="0">
                <v-text-field v-model="search" class="mb-1" prepend-icon="search" label="Buscar" tabindex="1" dense>
                </v-text-field>
                <v-data-table :headers="headers" :items="data" :items-per-page="10" :footer-props="{
                    showFirstLastPage: true,
                    firstIcon: 'first_page',
                    lastIcon: 'last_page',
                    prevIcon: 'chevron_left',
                    nextIcon: 'chevron_right'
                }" :loading="loading_table" loading-text="Obteniendo información"
                    no-data-text="No se ha obtenido información" no-results-text="No se obtuvieron resultados"
                    multi-sort :search="search" fixed-header align="center">
                    <template v-slot:item.status="{ item }">
                        <div>
                            <template v-if="item.status == 0">
                                <v-btn icon @click.prevent="statusSubject(item.id, 1)">
                                    <v-icon>
                                        check_box_outline_blank
                                    </v-icon>
                                </v-btn>
                            </template>
                            <template v-else-if="item.status == 1">
                                <v-btn icon @click.prevent="statusSubject(item.id, 0)">
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
                                    :to='{ name: "editSubject", params: { id: item.id } }'>
                                    <v-icon>
                                        settings
                                    </v-icon>
                                </v-btn>
                            </template>
                            <span>Ver curso</span>
                        </v-tooltip>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn icon class="txt_red" @click.prevent="deleteSubject(item.id)" v-bind="attrs"
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
    name: "HomeSubject",
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
        this.allSubjects();
    },
    methods: {
        async allSubjects() {
            await this.axios.get('/api/subject')
                .then(response => {
                    this.data = response.data;
                    this.loading_table = false;
                })
                .catch(error => {
                    this.data = [];
                    this.loading_table = false;
                })
        },
        async deleteSubject(item) {
            await this.$swal({
                title: '¿Esta seguro de eliminar el curso?',
                text: "Esta acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            })
                .then(result => {
                    if (result.isConfirmed) {
                        this.overlay = true;
                        this.axios.delete('/api/subject/' + item)
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
                                this.allSubjects();
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
                                this.allSubjects();
                                this.overlay = false;
                            });
                    }
                });
        },
        async statusSubject(item, type) {
            await this.$swal({
                title: '¿Esta seguro de cambiar el estado del curso?',
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
                        this.axios.post('/api/subject/status/' + item, data)
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
                                this.allSubjects();
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
                                this.allSubjects();
                                this.overlay = false;
                            });
                    }
                });
        }
    },
}
</script>