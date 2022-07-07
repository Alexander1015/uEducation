<template>
    <v-main>
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <div class="ml-16">
            <template v-if="overlay == false">
                <v-card class="mt-2 mx-auto" elevation="2" max-width="1200">
                    <!-- Imagen -->
                    <v-img class="mx-auto" :src='topics.img' :lazy-src='topics.lazy_img' max-height="250">
                        <template v-slot:placeholder>
                            <v-row class="fill-height ma-0" align="center" justify="center">
                                <v-progress-circular indeterminate color="grey lighten-5">
                                </v-progress-circular>
                            </v-row>
                        </template>
                    </v-img>
                    <!-- Titulo -->
                    <v-card-title>
                        <span class="text-h5 text-uppercase font-weight-bold mx-1">
                            {{ topics.name }}
                        </span>
                    </v-card-title>
                    <!-- Tags -->
                    <template v-if="topics.tags.length > 0">
                        <div class="mt-n1 mx-6">
                            <template v-for="item in topics.tags">
                                <v-chip label :color="item.background_color" :style='"color:" + item.text_color + ";"'>
                                    <v-icon left>label</v-icon> {{ item.name }}
                                </v-chip>
                            </template>
                        </div>
                    </template>
                    <!-- Usuario -->
                    <v-card-text class="mt-0">
                        <v-list-item>
                            <v-list-item-avatar>
                                <v-img :src="topics.user_update ? topics.user_avatar_update : topics.user_avatar"
                                    max-height="38" max-width="38"
                                    :lazy-src="topics.user_update ? topics.user_lazy_avatar_update : topics.user_lazy_avatar">
                                    <template v-slot:placeholder>
                                        <v-row class="fill-height ma-0" align="center" justify="center">
                                            <v-progress-circular indeterminate color="grey lighten-5">
                                            </v-progress-circular>
                                        </v-row>
                                    </template>
                                </v-img>
                            </v-list-item-avatar>
                            <v-list-item-title>
                                <span class="subtitle-1 font-italic font-weight-bold">
                                    {{ topics.user_update ? topics.user_update : topics.user }}
                                </span>
                                <br />
                                <span class="subtitle-2 font-italic">
                                    Contacto:
                                    <a
                                        :href="'mailto:' + (topics.user_update ? topics.user_update_email : topics.user_emai)">
                                        {{ topics.user_update ? topics.user_update_email : topics.user_email }}
                                    </a>
                                </span>
                                <br />
                                <span class="subtitle-2 font-italic">
                                    Ultima actualización: {{ topics.user_update ? topics.updated_at : topics.created_at
                                    }}
                                </span>
                            </v-list-item-title>
                        </v-list-item>
                    </v-card-text>
                    <!-- Abstract -->
                    <template v-if="topics.abstract">
                        <v-divider></v-divider>
                        <v-card-text>
                            <div id="data_abstract" class="bk_tags_bk txt_black mx-2 pa-2">
                                <span class="body-2">
                                    {{ topics.abstract }}
                                </span>
                            </div>
                        </v-card-text>
                    </template>
                    <!-- Divider -->
                    <v-divider></v-divider>
                    <!-- Body -->
                    <div id="data_body" class="mt-4 mx-3">
                        <ckeditor :editor="editor" v-model="topics.body" :config="editorConfig"
                            :disabled="editorDisabled">
                        </ckeditor>
                    </div>
                    <!-- Boton regresar y avanzar -->
                    <template v-if="previousTopic.slug || nextTopic.slug">
                        <div class="py-2 px-4">
                            <v-row>
                                <template v-if="previousTopic.slug">
                                    <v-col cols="12" sm="6" md="4" class="mx-auto">
                                        <v-btn class="width_100 py-4" height="50" plain small
                                            :to='{ name: "publicTopic", params: { topic: previousTopic.slug } }'>
                                            <v-icon left>keyboard_double_arrow_left</v-icon>
                                            <span>
                                                Anterior
                                                <br />
                                                <small>
                                                    {{ previousTopic.name }}
                                                </small>
                                            </span>
                                        </v-btn>
                                    </v-col>
                                </template>
                                <template v-if="nextTopic.slug">
                                    <v-col cols="12" sm="6" md="4" class="mx-auto">
                                        <v-btn class="width_100 py-4" height="50" plain small
                                            :to='{ name: "publicTopic", params: { topic: nextTopic.slug } }'>
                                            <span>
                                                Siguiente
                                                <br />
                                                <small>
                                                    {{ nextTopic.name }}
                                                </small>
                                            </span>
                                            <v-icon right>keyboard_double_arrow_right</v-icon>
                                        </v-btn>
                                    </v-col>
                                </template>
                            </v-row>
                        </div>
                    </template>
                </v-card>
            </template>
            <template v-else>
                <v-row>
                    <v-col cols="12" class="mt-2 mx-auto">
                        <v-skeleton-loader v-bind="attrs" type="list-item-avatar-three-line, image, article">
                        </v-skeleton-loader>
                    </v-col>
                </v-row>
            </template>
        </div>
    </v-main>
</template>

<script>
import DecoupledEditor from '@ckeditor/ckeditor5-build-decoupled-document';
import '@ckeditor/ckeditor5-build-classic/build/translations/es';

export default {
    name: 'PublicTopic',
    data: () => ({
        editor: DecoupledEditor,
        editorDisabled: true,
        editorConfig: {
            language: 'es',
            placeholder: '',
        },
        overlay: false,
        attrs: {
            class: 'mb-6',
            elevation: 2,
            loading: true,
        },
        topics: {
            name: "",
            abstract: "",
            body: "",
            created_at: "",
            updated_at: "",
            img: "",
            lazy_img: "",
            user: "",
            user_email: "",
            user_avatar: "",
            user_lazy_avatar: "",
            user_update: "",
            user_update_email: "",
            user_avatar_update: "",
            user_lazy_avatar_update: "",
            subject: "",
            tags: [],
        },
        previousTopic: {
            slug: "",
            name: "",
        },
        nextTopic: {
            slug: "",
            name: "",
        },
    }),
    mounted() {
        this.getTopic();
    },
    computed: {
        address() {
            return this.$route.params.topic;
        }
    },
    watch: {
        address() {
            this.getTopic();
        }
    },
    methods: {
        async getTopic() {
            this.overlay = true;
            if (this.$route.params.subject && this.address) {
                await this.axios.get('/api/gettopic/' + this.address)
                    .then(response => {
                        const item = response.data;
                        if (!item.topic) {
                            this.overlay = false;
                            this.$router.push({ name: "publicSubject", params: { subject: this.$route.params.subject } });
                        }
                        else {
                            this.topics.name = item.topic.name;
                            this.topics.abstract = item.topic.abstract;
                            this.topics.user = item.topic.user;
                            this.topics.user_email = item.topic.user_email;
                            this.topics.user_update = item.topic.user_update;
                            this.topics.user_update_email = item.topic.user_update_email;
                            this.topics.subject = item.topic.subject;
                            this.topics.created_at = item.topic.created_at;
                            this.topics.updated_at = item.topic.updated_at;
                            this.topics.body = item.topic.body;
                            // Imagen del topics
                            if (item.topic.img) {
                                this.topics.img = "/img/topics/" + item.topic.img;
                                this.topics.lazy_img = "/img/lazy_topics/" + item.topic.img;
                            }
                            else {
                                this.topics.img = "/img/topics/blank.png";
                                this.topics.lazy_img = "/img/lazy_topics/blank.png";
                            }
                            // Imagenes de los usuarios
                            if (item.topic.user_avatar) {
                                this.topics.user_avatar = "/img/users/" + item.topic.user_avatar;
                                this.topics.user_lazy_avatar = "/img/lazy_users/" + item.topic.user_avatar;
                            }
                            else {
                                this.topics.user_avatar = "/img/users/blank.png";
                                this.topics.user_lazy_avatar = "/img/lazy_users/blank.png";
                            }
                            // Imagenes de los usuarios
                            if (item.topic.user_update_avatar) {
                                this.topics.user_avatar_update = "/img/users/" + item.topic.user_update_avatar;
                                this.topics.user_lazy_avatar_update = "/img/lazy_users/" + item.topic.user_update_avatar;
                            }
                            else {
                                this.topics.user_avatar_update = "/img/users/blank.png";
                                this.topics.user_lazy_avatar_update = "/img/lazy_users/blank.png";
                            }
                            // Anterior
                            if (item.previous.slug) {
                                this.previousTopic.slug = item.previous.slug;
                                this.previousTopic.name = item.previous.name;
                            }
                            else {
                                this.previousTopic.slug = "";
                                this.previousTopic.name = "";
                            }
                            //Siguiente
                            if (item.next.slug) {
                                this.nextTopic.slug = item.next.slug;
                                this.nextTopic.name = item.next.name;
                            }
                            else {
                                this.nextTopic.slug = "";
                                this.nextTopic.name = "";
                            }
                            // Tags
                            if (item.tags) this.topics.tags = item.tags;
                            else this.topics.tags = [];
                            this.overlay = false;
                        }
                    }).catch((error) => {
                        console.log(error);
                        this.overlay = false;
                        this.$router.push("/");
                    });
            }
            else if (this.$route.params.subject && !this.address) {
                this.overlay = false;
                this.$router.push({ name: "publicSubject", params: { subject: this.$route.params.subject } });
            }
            else {
                this.overlay = false;
                this.$router.push("/");
            }
        }
    },
}
</script>