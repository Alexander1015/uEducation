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
                        <v-btn class="mt-n1" v-bind="attrs" v-on="on" icon @click.stop="records()">
                            <v-icon>autorenew</v-icon>
                        </v-btn>
                    </template>
                    <span>Actualizar</span>
                </v-tooltip>
                <span class="text-h6">REGISTROS</span>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn class="ml-4 mt-n2 bk_blue txt_white" v-bind="attrs" v-on="on" @click.stop="gotoNew()">
                            <v-icon>folder_copy</v-icon>
                        </v-btn>
                    </template>
                    <span>Generar la bitácora del ciclo</span>
                </v-tooltip>
            </div>
            <div class="mb-8">
                <p class="text-justify">
                    Bitácora de registros de las suscripciones de docentes y estudiantes.
                </p>
                <!-- Formulario -->
                <v-form ref="form" @submit.prevent="searchRecord()" lazy-validation>
                    <v-row class="mb-1">
                        <!-- Period -->
                        <v-col cols="12" sm="5">
                            <v-autocomplete v-model="period" :items="data_period" :rules="periodRules" clearable
                                clear-icon="cancel" label="Buscar por ciclo inscrito" tabindex="1" dense
                                :loading="overlay" no-data-text="No se encuentra información para mostrar"
                                prepend-icon="today" append-icon="arrow_drop_down" hide-selected required>
                            </v-autocomplete>
                        </v-col>
                        <!-- Year -->
                        <v-col cols="12" sm="5">
                            <v-autocomplete v-model="year" :items="data_year" :rules="yearRules" clearable
                                clear-icon="cancel" label="Buscar por año de registro" tabindex="2" dense
                                :loading="overlay" no-data-text="No se encuentra información para mostrar"
                                prepend-icon="event" append-icon="arrow_drop_down" hide-selected required>
                            </v-autocomplete>
                        </v-col>
                        <v-col cols="12" sm="2">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn class="bk_blue txt_white width_100" v-bind="attrs" v-on="on" type="submit">
                                        <v-icon>manage_search</v-icon>
                                    </v-btn>
                                </template>
                                <span>Buscar registros</span>
                            </v-tooltip>
                        </v-col>
                    </v-row>
                </v-form>
                <template v-if="view">
                    <v-row class="mb-1">
                        <!-- Search -->
                        <v-col cols="12" sm="6">
                            <v-text-field v-model="search" tabindex="3" prepend-icon="search" label="Búsqueda general"
                                dense>
                            </v-text-field>
                        </v-col>
                        <!-- Subjects -->
                        <v-col cols="12" sm="6">
                            <v-autocomplete v-model="subjects" :items="data_subject" clearable clear-icon="cancel"
                                label="Buscar por código del curso" tabindex="4" dense :loading="loading_table"
                                item-text="code" no-data-text="No se encuentra información para mostrar"
                                prepend-icon="collections_bookmark" append-icon="arrow_drop_down" hide-selected
                                required>
                                <template v-slot:selection="data">
                                    {{ data.item.code }} ({{ data.item.name }})
                                </template>
                                <template v-slot:item="data">
                                    <v-list-item-content>
                                        {{ data.item.code }} ({{ data.item.name }})
                                    </v-list-item-content>
                                </template>
                            </v-autocomplete>
                        </v-col>
                    </v-row>
                    <v-data-table :headers="headers" :items="data" :items-per-page="10" :footer-props="{
                        showFirstLastPage: true,
                        firstIcon: 'first_page',
                        lastIcon: 'last_page',
                        prevIcon: 'chevron_left',
                        nextIcon: 'chevron_right'
                    }" :loading="loading_table" loading-text="Obteniendo información"
                        no-data-text="No se ha obtenido información" no-results-text="No se obtuvieron resultados"
                        multi-sort :search="search" fixed-header align="center">
                    </v-data-table>
                </template>
            </div>
        </div>
    </v-main>
</template>

<script>
export default {
    name: "HomeRecords",
    data: () => ({
        overlay: false,
        user: {},
        view: false,
        period: '',
        year: '',
        search: '',
        loading_table: true,
        subjects: '',
        data: [],
        data_subject: [],
        data_period: [
            "01",
            "02",
            "03"
        ],
        data_year: [],
        periodRules: [
            v => !!v || 'El ciclo es requerido',
        ],
        yearRules: [
            v => !!v || 'El año es requerido',
        ],
    }),
    computed: {
        headers() {
            return [
                { text: 'Nombre del usuario', value: 'firstname', align: 'center' },
                { text: 'Apellido del usuario', value: 'lastname', align: 'center' },
                { text: 'Usuario', value: 'user', align: 'center' },
                { text: 'Tipo de usuario', value: 'type', align: 'center' },
                { text: 'Curso', value: 'subject', align: 'center' },
                {
                    text: 'Código del curso', value: 'code', align: 'center',
                    filter: value => {
                        return value.toString().toLowerCase().includes((this.subjects ? this.subjects : "").toLowerCase())
                    },
                },
            ]
        }
    },
    mounted() {
        this.records();
    },
    methods: {
        gotoNew() {
            this.overlay = true;
            this.$router.push({ name: "newRecord" });
        },
        async records() {
            this.overlay = true;
            this.view = false;
            this.data = [];
            this.data_subject = [];
            await this.axios.get('/api/auth')
                .then(response => {
                    this.user = response.data;
                }).catch((error) => {
                    console.log(error);
                    this.axios.post('/api/logout')
                        .then(response => {
                            window.location.href = "/auth"
                        }).catch((error) => {
                            console.log(error);
                        });
                });
            await this.axios.get('/api/record')
                .then(response => {
                    this.data_year = response.data;
                    this.overlay = false;
                }).catch((error) => {
                    console.log(error);
                    this.overlay = false;
                });
        },
        async searchRecord() {
            if (this.$refs.form.validate()) {
                this.overlay = true;
                this.loading_table = true;
                let data = new FormData();
                data.append('period', this.period);
                data.append('year', this.year);
                await this.axios.post('/api/record/list', data)
                    .then(response => {
                        const items = response.data;
                        this.data = items.data;
                        this.data_subject = items.subjects;
                        this.view = true;
                        this.loading_table = false;
                        this.overlay = false;
                    }).catch(error => {
                        console.log(error);
                        this.view = false;
                        this.loading_table = false;
                        this.overlay = false;
                    });
            }
        }
    },
}
</script>