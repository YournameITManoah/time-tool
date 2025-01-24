/**
 * Use validation rules with i18n
 * @returns Validation rules
 */
export function useValidation() {
    const { t } = useI18n()

    const isRequired = (v: boolean | number | string) =>
        (v !== undefined && v !== null && v !== '') || t('validation.required')

    const maxLength = (max: number) => (v: string) =>
        !v || v.length <= max || t('validation.max.string', { max })

    return { isRequired, maxLength }
}
