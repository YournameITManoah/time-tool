<script setup lang="ts">
import AppLayout from '@/layouts/app.vue'

defineOptions({ name: 'WelcomePage' })

useHead({ title: 'Welcome' })

defineProps<{
    canLogin?: boolean
    canRegister?: boolean
    phpVersion: string
    laravelVersion: string
}>()

const user = useProperty('auth.user')
</script>

<template>
    <app-layout>
        <v-container class="fill-height">
            <v-row align="center" justify="center">
                <v-col cols="12" lg="4" md="10" sm="12">
                    <v-card class="pa-3 rounded-lg elevation-8">
                        <v-card-title>
                            <div class="d-flex justify-center mb-4">
                                <ApplicationLogo
                                    class="mr-1"
                                    style="height: 75px"
                                />
                                <VuetifyLogo
                                    class="ml-1"
                                    style="height: 75px"
                                />
                            </div>
                            <span class="text-h5">
                                Welcome to Laravel + Vuetify
                            </span>
                        </v-card-title>
                        <v-card-text>
                            <p class="text-body-2">
                                Laravel has wonderful documentation covering
                                every aspect of the framework. Whether you are a
                                newcomer or have prior experience with Laravel,
                                we recommend reading our documentation from
                                beginning to end.
                            </p>
                        </v-card-text>
                        <v-card-actions>
                            <template v-if="user">
                                <router-link :href="route('dashboard')">
                                    <v-btn tabindex="-1" color="primary">
                                        Dashboard
                                    </v-btn>
                                </router-link>
                            </template>
                            <template v-else>
                                <router-link
                                    v-if="canLogin"
                                    :href="route('login')"
                                    class="mr-2"
                                >
                                    <v-btn tabindex="-1" color="primary">
                                        Login
                                    </v-btn>
                                </router-link>
                                <router-link
                                    v-if="canRegister"
                                    :href="route('register')"
                                >
                                    <v-btn tabindex="-1" color="secondary">
                                        Sign Up
                                    </v-btn>
                                </router-link>
                            </template>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </app-layout>
</template>
