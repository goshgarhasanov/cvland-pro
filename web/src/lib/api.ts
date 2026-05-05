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
      const fromAuth = window.location.pathname.startsWith('/auth/')
      if (!fromAuth) {
        window.dispatchEvent(new CustomEvent('auth:unauthenticated'))
      }
    }
    return Promise.reject(error)
  },
)
