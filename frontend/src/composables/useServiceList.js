import { computed } from 'vue'
import { useServiceListStore } from '@/stores/serviceList'
import Swal from 'sweetalert2'

export default function useServiceList() {
  const service = useServiceListStore()

  const fetchServiceList = async (force = false) => {
    const result = await service.fetchServiceList(force)
    if (service.error) {
      Swal.fire('Error', service.error, 'error')
    }
    return result
  }

  const refreshCache = async () => {
    await fetchServiceList(true)
    Swal.fire('Updated!', 'Data service telah diperbarui', 'success')
  }

  const addCustomer = async (name, no_telp) => {
    try {
      await service.addCustomer(name, no_telp)
      Swal.fire('Berhasil!', 'Berhasil menambahkan customer', 'success')
    } catch (e) {
      console.error('Gagal menambah user : ', e)
      Swal.fire('Gagal', 'Gagal Menambah Pelanggan', 'error')
    }
  }

  const getCustomer = async () => {
    return await service.getData()
  }

  return {
    serviceList: computed(() => service.serviceList),
    loading: computed(() => service.loading),
    error: computed(() => service.error),
    lastFetched: computed(() => service.lastFetched),
    fetchServiceList,
    addCustomer,
    getCustomer,
    refreshCache,
    invalidateCache: service.invalidateCache
  }
}
