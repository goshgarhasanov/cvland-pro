import axios, { AxiosError, type AxiosInstance } from 'axios'

const baseURL = import.meta.env.VITE_API_URL ?? '/api'

export const api: AxiosInstance = axios.create({
  baseURL,
  withCredentials: true,
  withXSRFToken: true,
  headers: {
    Accept: 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  },
})

let csrfFetched = false

async function ensureCsrfCookie(): Promise<void> {
  if (csrfFetched) return
  await axios.get('/sanctum/csrf-cookie', { withCredentials: true })
  csrfFetched = true
}

api.interceptors.request.use(async (config) => {
  const method = config.method?.toLowerCase()
  if (method && ['post', 'put', 'patch', 'delete'].includes(method)) {
    await ensureCsrfCookie()
  }
  return config
})

api.interceptors.response.use(
  (response) => response,
  (error: AxiosError) => {
    if (error.response?.status === 401) {
      const url = error.config?.url ?? ''
      const isAuthCheck = url.includes('/auth/me') || url.includes('/auth/login')
      const onAuthPage = window.location.pathname.startsWith('/auth/')
      const onPublicPage = window.location.pathname === '/' || window.location.pathname.startsWith('/templates')
      if (!isAuthCheck && !onAuthPage && !onPublicPage) {
        window.dispatchEvent(new CustomEvent('auth:unauthenticated'))
      }
    }
    return Promise.reject(error)
  },
)
