<template>
    <default-layout v-if="userId">
        <div class="mb-5">
            <h5 class="text-h5 font-weight-bold">
                {{ status }} {{ description }}
            </h5>
        </div>
        <v-card
            :title="t('Unexpected Error')"
            :text="t('messages.unexpected_error_text')"
        >
            <v-card-actions>
                <router-link :href="route('home')" :aria-label="t('Go Back')">
                    <v-btn
                        tabindex="-1"
                        color="primary"
                        aria-hidden="true"
                        :text="t('Go Back')"
                    />
                </router-link>
            </v-card-actions>
        </v-card>
    </default-layout>
    <guest-layout v-else>
        <v-card-title>{{ status }} {{ description }}</v-card-title>
        <v-card-text>
            <p class="text-body-2">
                {{ t('messages.unexpected_error_text') }}
            </p>
        </v-card-text>
        <v-card-actions>
            <router-link :href="route('dashboard')" :aria-label="t('Go Back')">
                <v-btn
                    tabindex="-1"
                    aria-hidden="true"
                    color="primary"
                    :text="t('Go Back')"
                />
            </router-link>
        </v-card-actions>
    </guest-layout>
</template>
<script lang="ts" setup>
import DefaultLayout from '@/layouts/default.vue'
import GuestLayout from '@/layouts/guest.vue'

defineOptions({ name: 'ErrorPage' })

const { t } = useI18n()

const userId = useProperty('auth.user.id')
const props = defineProps<{ status: number }>()

const description = computed(() => {
    switch (props.status) {
        case 401:
            return 'Unauthorized'
        case 403:
            return 'Forbidden'
        case 404:
            return 'Not Found'
        case 500:
            return 'Internal Server Error'
        case 503:
            return 'Service Unavailable'
        default:
            return 'Unknown Error'
    }
})

useHead({ title: `${props.status} ${description.value}` })
</script>
