import { config } from '@vue/test-utils'
import { createI18n } from 'vue-i18n'
import { createPinia, setActivePinia } from 'pinia'
import { beforeEach } from 'vitest'

const i18n = createI18n({
  legacy: false,
  locale: 'en',
  messages: {
    en: { common: { loading: 'Loading…' } },
  },
})

config.global.plugins = [i18n]

beforeEach(() => {
  setActivePinia(createPinia())
})
