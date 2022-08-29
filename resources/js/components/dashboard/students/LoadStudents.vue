<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <v-card class="mx-auto" elevation="0">
            <div class="pb-4 mx-4">
                <v-btn class="mr-4 mt-2" text small @click.stop="returnStudents()">
                    <v-icon left>keyboard_double_arrow_left</v-icon>
                    Regresar
                </v-btn>
                <v-card-title class="text-h5">
                    <p class="mx-auto">CARGAR ESTUDIANTES</p>
                </v-card-title>
                <v-stepper class="mx-auto" v-model="step" alt-labels max-width="1100">
                    <v-stepper-header>
                        <v-stepper-step step="1" class="caption" :complete="step > 1" complete-icon="done">
                            Cargar archivo
                        </v-stepper-step>
                        <v-divider></v-divider>
                        <v-stepper-step step="2" class="caption" :complete="step > 2" complete-icon="done">
                            Vista previa de los datos
                        </v-stepper-step>
                        <v-divider></v-divider>
                        <v-stepper-step step="3" class="caption" :complete="step > 3" complete-icon="done">
                            Datos cargados
                        </v-stepper-step>
                    </v-stepper-header>
                    <v-stepper-items>
                        <!-- Cargar archivo -->
                        <v-stepper-content step="1">
                            <div class="mt-2 mb-4">
                                <p class="text-justify">
                                    En este apartado puede subir la informacion de los estudiantes; para cargar dicha
                                    información
                                    proporcione un archivo de excel teniendo en cuenta el siguiente
                                    formato:
                                </p>
                                <p class="text-center">
                                    <small class="font-italic txt_red">
                                        Importante: El documento de excel no debe poseer cabeceras, y tampoco
                                        contraseñas debido a que estas se pondran de forma predeterminada como el
                                        "Usuario" del mismo
                                    </small>
                                </p>
                                <v-img class="my-2 mx-auto" :src="banner.img" :lazy-src='banner.lazy'
                                    :max-height="banner.height" :max-width="banner.width" contain>
                                    <template v-slot:placeholder>
                                        <v-row class="fill-height ma-0" align="center" justify="center">
                                            <v-progress-circular indeterminate color="grey lighten-5">
                                            </v-progress-circular>
                                        </v-row>
                                    </template>
                                </v-img>
                                <v-row class="mt-3">
                                    <v-col>
                                        <v-file-input v-model="file" @change="uploadExcel()"
                                            label="Haz clic(k) aquí para subir el archivo de excel" id="excel"
                                            prepend-icon="table_view"
                                            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                            show-size dense>
                                        </v-file-input>
                                    </v-col>
                                    <template v-if="file">
                                        <v-col cols="12" sm="6">
                                            <v-btn class="bk_brown txt_white" block @click.stop="clean_file()">
                                                <v-icon left>delete</v-icon>
                                                Borrar documento
                                            </v-btn>
                                        </v-col>
                                    </template>
                                </v-row>
                            </div>
                            <template v-if="data.length > 0">
                                <v-btn class="txt_white bk_blue" block @click.stop="step = 2">
                                    Siguiente
                                    <v-icon right>arrow_forward</v-icon>
                                </v-btn>
                            </template>
                            <template v-else>
                                <v-btn block disabled>
                                    Siguiente
                                    <v-icon right>arrow_forward</v-icon>
                                </v-btn>
                            </template>
                        </v-stepper-content>
                        <!-- Vista previa -->
                        <v-stepper-content step="2">
                            <template v-if="data.length > 0">
                                <div class="mb-4">
                                    <v-text-field v-model="search" class="ml-2 mb-1" prepend-icon="search"
                                        label="Buscar" dense>
                                    </v-text-field>
                                    <v-data-table :headers="headers" :items="data" :items-per-page="10" :footer-props="{
                                        showFirstLastPage: true,
                                        firstIcon: 'first_page',
                                        lastIcon: 'last_page',
                                        prevIcon: 'chevron_left',
                                        nextIcon: 'chevron_right'
                                    }" no-data-text="No se ha obtenido información"
                                        no-results-text="No se obtuvieron resultados" multi-sort :search="search"
                                        fixed-header align="center">

                                    </v-data-table>
                                </div>
                                <v-row>
                                    <v-col cols="12" sm="6" class="my-1">
                                        <v-btn class="txt_white bk_red" block @click.stop="step = 1">
                                            <v-icon left>arrow_back</v-icon>
                                            Regresar
                                        </v-btn>
                                    </v-col>
                                    <v-col cols="12" sm="6" class="my-1">
                                        <v-btn class="txt_white bk_blue" block @click.stop="loadUsers()">
                                            Cargar datos
                                            <v-icon right>drive_folder_upload</v-icon>
                                        </v-btn>
                                    </v-col>
                                </v-row>
                            </template>
                        </v-stepper-content>
                        <!-- Fin -->
                        <v-stepper-content step="3">
                            <div class="mb-4">
                                <p class="text-justify">
                                    {{  message  }}
                                </p>
                            </div>
                            <template v-if="data_fail.length > 0">
                                <div class="mb-4">
                                    <v-row>
                                        <v-col cols="12" md="9">
                                            <v-text-field v-model="search_fail" prepend-icon="search" label="Buscar"
                                                dense>
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="3">
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on, attrs }">
                                                    <v-btn class="bk_blue txt_white width_100" v-bind="attrs" v-on="on"
                                                        @click.stop="downloadExcel()">
                                                        <v-icon left>download</v-icon>
                                                        Descargar
                                                    </v-btn>
                                                </template>
                                                <span>Descargar datos devueltos como "xls"</span>
                                            </v-tooltip>
                                        </v-col>
                                    </v-row>
                                    <v-data-table class="mt-2" :headers="headers" :items="data_fail"
                                        :items-per-page="10" :footer-props="{
                                            showFirstLastPage: true,
                                            firstIcon: 'first_page',
                                            lastIcon: 'last_page',
                                            prevIcon: 'chevron_left',
                                            nextIcon: 'chevron_right'
                                        }" no-data-text="No se ha obtenido información"
                                        no-results-text="No se obtuvieron resultados" multi-sort :search="search_fail"
                                        fixed-header align="center">

                                    </v-data-table>
                                </div>
                            </template>
                            <v-btn class="txt_white bk_green" block @click.stop="returnStudents()">
                                <v-icon left>keyboard_double_arrow_left</v-icon>
                                Terminar
                            </v-btn>
                        </v-stepper-content>
                    </v-stepper-items>
                </v-stepper>
            </div>
        </v-card>
    </v-main>
</template>

<script>
import readXlsFile from "read-excel-file";
import exportXlsFile from "export-from-json";

export default {
    name: "LoadUsers",
    data: () => ({
        overlay: false,
        banner: {
            img: "/img/loads/users.png",
            lazy: "/img/loads/users_lazy.png",
            height: 150,
            width: 1000,
        },
        headers: [
            { text: 'Nombre', value: 'firstname', align: 'center' },
            { text: 'Apellido', value: 'lastname', align: 'center' },
            { text: 'Correo electrónico', value: 'email', align: 'center' },
            { text: 'Usuario', value: 'user', align: 'center' },
        ],
        login_user: [],
        search: '',
        search_fail: '',
        step: 1,
        file: null,
        data: [],
        message: "",
        data_fail: [],
    }),
    mounted() {
        this.initUsers();
    },
    methods: {
        returnStudents() {
            this.overlay = true;
            this.$router.push({ name: "students" });
        },
        uploadExcel() {
            this.data = [];
            const input = document.querySelector('#excel').files[0];
            readXlsFile(input).then((rows) => {
                if (rows.length > 0) {
                    for (let item of rows) {
                        this.data.push({
                            "firstname": item[0],
                            "lastname": item[1],
                            "email": item[2],
                            "user": item[3],
                        });
                    }
                }
            });
        },
        downloadExcel() {
            if (this.data_fail.length > 0) {
                const data = this.data_fail;
                const fileName = 'download';
                const exportType = exportXlsFile.types.xls;
                exportXlsFile({ data, fileName, exportType })
            }
        },
        clean_file() {
            this.file = null;
            this.data = [];
        },
        async initUsers() {
            this.overlay = true;
            // Obtenemos el usuario ingresado
            await this.axios.get('/api/auth')
                .then(response => {
                    this.login_user = response.data;
                    this.overlay = false;
                }).catch((error) => {
                    console.log(error);
                    this.axios.post('/api/logout')
                        .then(response => {
                            window.location.href = "/auth"
                        }).catch((error) => {
                            console.log(error);
                            this.$router.push({ name: "forbiden" });
                        });
                });
        },
        async loadUsers() {
            if (this.data.length > 0 && this.login_user.type == '0') {
                await this.$swal({
                    title: '¿Esta seguro de subir estos estudiantes?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'Cancelar',
                })
                    .then(result => {
                        if (result.isConfirmed) {
                            this.overlay = true;
                            let data = new FormData();
                            for (let item of this.data) {
                                data.append('firstname[]', item.firstname == null ? "" : item.firstname);
                            }
                            for (let item of this.data) {
                                data.append('lastname[]', item.lastname == null ? "" : item.lastname);
                            }
                            for (let item of this.data) {
                                data.append('email[]', item.email == null ? "" : item.email);
                            }
                            for (let item of this.data) {
                                data.append('user[]', item.user == null ? "" : item.user);
                            }
                            this.axios.post('/api/loadstudents/', data)
                                .then(response => {
                                    if (!response.data.complete) {
                                        let title = "Error";
                                        let icon = "error";
                                        this.$swal({
                                            title: title,
                                            icon: icon,
                                            text: response.data.message,
                                        }).then(() => {
                                            this.overlay = false;
                                        });
                                    }
                                    else {
                                        this.message = response.data.message;
                                        this.data_fail = response.data.fail;
                                        this.step = 3;
                                        this.overlay = false;
                                    }
                                }).catch(error => {
                                    this.$swal({
                                        title: "Error",
                                        icon: "error",
                                        text: "Ha ocurrido un error en la aplicación",
                                    }).then(() => {
                                        console.log(error);
                                        this.overlay = false;
                                    });
                                })
                        }
                    });
            }
        }
    },
}
</script>