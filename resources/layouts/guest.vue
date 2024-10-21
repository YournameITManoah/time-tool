<script setup lang="ts">
import AppLayout from '@/layouts/app.vue'

defineOptions({ name: 'GuestLayout' })
</script>

<template>
    <app-layout>
        <v-container class="fill-height">
            <v-row align="center" justify="center">
                <v-col cols="12" sm="10" md="6" lg="4">
                    <div class="d-flex justify-center">
                        <router-link href="/">
                            <ApplicationLogo style="height: 75px" />
                        </router-link>
                    </div>
                    <v-card class="px-6 py-4 mt-3 elevation-2 rounded-lg">
                        <slot />
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </app-layout>
</template>
