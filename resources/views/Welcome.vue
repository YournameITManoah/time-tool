<script setup lang="ts">
defineOptions({ name: 'WelcomePage' })

useHead({ title: 'Welcome' })

defineProps<{
    canLogin?: boolean
    canRegister?: boolean
    phpVersion: string
    laravelVersion: string
}>()

const user = useProperty('auth.user')
const name = import.meta.env.VITE_APP_NAME
</script>

<template layout="guest">
    <div>
        <v-card-title>Welcome to {{ name }}!</v-card-title>
        <v-card-text>
            <p class="text-body-2">
                This tool is meant to simplify time registration for all parties
                involved.
            </p>
        </v-card-text>
        <v-card-actions>
            <template v-if="user">
                <router-link :href="route('dashboard')">
                    <v-btn tabindex="-1" color="primary"> Dashboard </v-btn>
                </router-link>
            </template>
            <template v-else>
                <router-link
                    v-if="canLogin"
                    :href="route('login')"
                    class="mr-2"
                >
                    <v-btn tabindex="-1" color="primary"> Login </v-btn>
                </router-link>
                <router-link v-if="canRegister" :href="route('register')">
                    <v-btn tabindex="-1" color="success"> Sign Up </v-btn>
                </router-link>
            </template>
        </v-card-actions>
    </div>
</template>
