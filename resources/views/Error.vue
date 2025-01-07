<template layout>
    <div>
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
                <router-link :href="route('dashboard')">
                    <v-btn tabindex="-1" color="primary" :text="t('Go Back')" />
                </router-link>
            </v-card-actions>
        </v-card>
    </div>
</template>
<script lang="ts" setup>
defineOptions({ name: 'ErrorPage' })

const { t } = useI18n()

const props = defineProps<{ status?: number }>()

const description = computed(() => {
    switch (props.status) {
        case 404:
            return 'Not Found'
        case 500:
            return 'Internal Server Error'
        case 501:
            return 'Not Implemented'
        case 502:
            return 'Bad Gateway'
        case 503:
            return 'Service Unavailable'
        case 504:
            return 'Gateway Timeout'
        default:
            return ''
    }
})

useHead({ title: `${props.status} ${description.value}` })
</script>
