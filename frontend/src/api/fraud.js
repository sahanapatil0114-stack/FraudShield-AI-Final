import axios from 'axios'
import { getApiBase } from './backend'

function fraudClient() {
  return axios.create({
    baseURL: getApiBase(),
    headers: { 'Content-Type': 'application/json' },
    timeout: 15000,
  })
}

export const fraudAPI = {
  detect: (data) => fraudClient().post('/api/fraud/detect.php', data),
  batchDetect: (transactions) => fraudClient().post('/api/fraud/batch.php', { transactions }),
  stats: () => fraudClient().get('/api/fraud/stats.php'),
  health: () => fraudClient().get('/api/health.php'),
}

export default fraudAPI
