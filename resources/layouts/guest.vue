<script setup lang="ts">
import AppLayout from '@/layouts/app.vue'

defineOptions({ name: 'GuestLayout' })
</script>

<template>
    <app-layout
        style="
            background: linear-gradient(
                to bottom right,
                #8b3687,
                #d30240,
                #e5321b,
                #f29100
            );
        "
    >
        <v-container class="fill-height" style="">
            <v-row align="center" justify="center">
                <v-col cols="12" sm="10" md="6" lg="4">
                    <v-card class="px-6 py-4 mt-3 elevation-2 rounded-lg">
                        <div
                            class="d-flex justify-space-between align-center py-4"
                        >
                            <div></div>
                            <router-link href="/" style="width: 50%">
                                <ApplicationLogo style="width: 100%" />
                            </router-link>
                            <locale-switcher button />
                        </div>
                        <slot />
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </app-layout>
</template>
