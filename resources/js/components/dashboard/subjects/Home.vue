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
                        <v-btn class="mt-n1" v-bind="attrs" v-on="on" icon @click.stop="allSubjects()">
                            <v-icon>autorenew</v-icon>
                        </v-btn>
                    </template>
                    <span>Actualizar</span>
                </v-tooltip>
                <span class="text-h6">MATERIAS</span>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn class="ml-4 mt-n2 bk_green txt_white" v-bind="attrs" v-on="on" @click.stop="gotoNew()">
                            <v-icon>add_box</v-icon>
                        </v-btn>
                    </template>
                    <span>Nuevo</span>
                </v-tooltip>
            </div>
            <div class="mb-8">
                <p>Listado de las materias existentes en la aplicación</p>
            </div>
            <v-text-field v-model="search" class="mb-1" prepend-icon="search" label="Buscar" tabindex="1" clearable
                clear-icon="cancel" dense>
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
                <template v-slot:item.img="{ item }">
                    <v-img class="mx-auto" :src='"/img/subjects/" + (item.img ? item.img : "blank.png")'
                        :lazy-src='"/img/lazy_subjects/" + (item.img ? item.img : "blank.png")' max-height="40"
                        max-width="60" contain>
                        <template v-slot:placeholder>
                            <v-row class="fill-height ma-0" align="center" justify="center">
                                <v-progress-circular indeterminate color="grey lighten-5">
                                </v-progress-circular>
                            </v-row>
                        </template>
                    </v-img>
                </template>
                <template v-slot:item.status="{ item }">
                    <div>
                        <template v-if="item.status == 0">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon v-bind="attrs" v-on="on" @click.stop="statusSubject(item.slug, 1)">
                                        <v-icon>
                                            check_box_outline_blank
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>Habilitar</span>
                            </v-tooltip>
                        </template>
                        <template v-else-if="item.status == 1">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon v-bind="attrs" v-on="on" @click.stop="statusSubject(item.slug, 0)">
                                        <v-icon>
                                            check_box
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>Deshabilitar</span>
                            </v-tooltip>
                        </template>
                        <template v-else>
                            <v-icon>indeterminate_check_box</v-icon>
                        </template>
                    </div>
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn icon class="txt_blue" v-bind="attrs" v-on="on" @click.stop="gotoEdit(item.slug)">
                                <v-icon>
                                    settings
                                </v-icon>
                            </v-btn>
                        </template>
                        <span>Ver materia</span>
                    </v-tooltip>
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn icon class="txt_red" @click.stop="deleteSubject(item.slug)" v-bind="attrs" v-on="on">
                                <v-icon>
                                    delete
                                </v-icon>
                            </v-btn>
                        </template>
                        <span>Eliminar</span>
                    </v-tooltip>
                </template>
            </v-data-table>
        </div>
    </v-main>
</template>

<script>
export default {
    name: "HomeSubject",
    data: () => ({
        overlay: false,
        loading_table: true,
        headers: [
            { text: 'Portada', value: 'img', align: 'center', sortable: false },
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
        gotoEdit(item) {
            this.overlay = true;
            this.$router.push({ name: "editSubject", params: { slug: item } });
        },
        gotoNew() {
            this.overlay = true;
            this.$router.push({ name: "newSubject" });
        },
        async allSubjects() {
            this.overlay = true;
            this.loading_table = true;
            this.data = [];
            await this.axios.get('/api/subject')
                .then(response => {
                    this.data = response.data;
                    this.loading_table = false;
                    this.overlay = false;
                })
                .catch(error => {
                    this.data = [];
                    this.loading_table = false;
                    this.overlay = false;
                })
        },
        async deleteSubject(item) {
            await this.$swal({
                title: '¿Esta seguro de eliminar la materia?',
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
                                    this.allSubjects();
                                })
                            })
                            .catch(error => {
                                this.sweet.title = "Error"
                                this.sweet.icon = "error";
                                this.$swal({
                                    title: this.sweet.title,
                                    icon: this.sweet.icon,
                                    text: "Ha ocurrido un error en la aplicación",
                                }).then(() => {
                                    console.log(error);
                                    this.overlay = false;
                                    this.allSubjects();
                                });
                            });
                    }
                });
        },
        async statusSubject(item, type) {
            await this.$swal({
                title: '¿Esta seguro de ' + (type == 1 ? "habilitar" : (type == 0 ? "deshabilitar" : "cambiar el estado de")) + ' la materia seleccionada?',
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
                                    this.allSubjects();
                                    this.overlay = false;
                                })
                            })
                            .catch(error => {
                                this.$swal({
                                    title: "Error",
                                    icon: "error",
                                    text: "Ha ocurrido un error en la aplicación",
                                }).then(() => {
                                    console.log(error);
                                    this.allSubjects();
                                    this.overlay = false;
                                });
                            });
                    }
                });
        }
    },
}
</script>