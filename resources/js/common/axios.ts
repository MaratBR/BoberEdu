import axios from 'axios'

let client = axios.create({
    baseURL: '/api'
})

export default client
