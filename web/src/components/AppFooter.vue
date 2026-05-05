<script setup lang="ts">
import { Github, Linkedin, Twitter, Heart } from 'lucide-vue-next'
import { useI18n } from 'vue-i18n'
import { RouterLink } from 'vue-router'

const { t } = useI18n()
const year = new Date().getFullYear()

const productLinks = [
  { href: '#features', key: 'footer.links.features' },
  { href: '#templates', key: 'footer.links.templates' },
  { href: '#faq', key: 'footer.links.faq' },
]

const companyLinks = [
  { href: '#', key: 'footer.links.about' },
  { href: '#', key: 'footer.links.contact' },
  { href: '#', key: 'footer.links.blog' },
]

const legalLinks = [
  { href: '#', key: 'footer.links.privacy' },
  { href: '#', key: 'footer.links.terms' },
  { href: '#', key: 'footer.links.cookies' },
]
</script>

<template>
  <footer class="border-t border-slate-800/80 bg-slate-950">
    <div class="mx-auto max-w-7xl px-6 py-16">
      <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-5">
        <div class="lg:col-span-2">
          <RouterLink :to="{ name: 'home' }" class="flex items-center gap-2 text-xl font-display font-bold text-white">
            <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-fuchsia-500 text-sm font-bold text-white shadow-lg shadow-brand-600/30">
              CV
            </span>
            CVLAND <span class="font-light text-brand-300">PRO</span>
          </RouterLink>
          <p class="mt-4 max-w-xs text-sm text-slate-400">{{ t('footer.tagline') }}</p>

          <div class="mt-6 flex items-center gap-3">
            <a href="#" aria-label="Twitter" class="grid h-9 w-9 place-items-center rounded-lg border border-slate-800 text-slate-400 transition-colors hover:border-brand-500/40 hover:text-brand-300">
              <Twitter class="h-4 w-4" />
            </a>
            <a href="#" aria-label="LinkedIn" class="grid h-9 w-9 place-items-center rounded-lg border border-slate-800 text-slate-400 transition-colors hover:border-brand-500/40 hover:text-brand-300">
              <Linkedin class="h-4 w-4" />
            </a>
            <a href="#" aria-label="GitHub" class="grid h-9 w-9 place-items-center rounded-lg border border-slate-800 text-slate-400 transition-colors hover:border-brand-500/40 hover:text-brand-300">
              <Github class="h-4 w-4" />
            </a>
          </div>
        </div>

        <div>
          <h3 class="text-sm font-semibold uppercase tracking-wider text-white">{{ t('footer.product') }}</h3>
          <ul class="mt-4 space-y-3">
            <li v-for="link in productLinks" :key="link.key">
              <a :href="link.href" class="text-sm text-slate-400 transition-colors hover:text-white">
                {{ t(link.key) }}
              </a>
            </li>
          </ul>
        </div>

        <div>
          <h3 class="text-sm font-semibold uppercase tracking-wider text-white">{{ t('footer.company') }}</h3>
          <ul class="mt-4 space-y-3">
            <li v-for="link in companyLinks" :key="link.key">
              <a :href="link.href" class="text-sm text-slate-400 transition-colors hover:text-white">
                {{ t(link.key) }}
              </a>
            </li>
          </ul>
        </div>

        <div>
          <h3 class="text-sm font-semibold uppercase tracking-wider text-white">{{ t('footer.legal') }}</h3>
          <ul class="mt-4 space-y-3">
            <li v-for="link in legalLinks" :key="link.key">
              <a :href="link.href" class="text-sm text-slate-400 transition-colors hover:text-white">
                {{ t(link.key) }}
              </a>
            </li>
          </ul>
        </div>
      </div>

      <div class="mt-12 flex flex-col items-center justify-between gap-3 border-t border-slate-800/80 pt-8 sm:flex-row">
        <p class="text-xs text-slate-500">© {{ year }} {{ t('app.name') }}. {{ t('footer.rights') }}</p>
        <p class="flex items-center gap-1.5 text-xs text-slate-500">
          {{ t('footer.made_with') }}
          <Heart class="h-3 w-3 fill-rose-500 text-rose-500" />
        </p>
      </div>
    </div>
  </footer>
</template>
