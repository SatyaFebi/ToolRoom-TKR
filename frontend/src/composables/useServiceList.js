import { ref, computed, watch } from 'vue'
import { useServiceListStore } from '@/stores/serviceList'
import Swal from 'sweetalert2'

export default function useServiceList() {
  const service = useServiceListStore()

  const isFetching = ref(false)

  const fetchServiceList = async (force = false) => {

    try {
      const result = await service.fetchServiceList(force)

      if (service.error) {
        Swal.fire('Error', service.error, 'error')
      }

      return result
    } catch (e) {
      console.error('Fetch service gagal: ', e)
      Swal.fire('Error', 'Fetch service gagal', 'error')
    } finally {
      isFetching.value = false
    }
  }

  const refreshCache = async () => {
    await fetchServiceList(true)
    Swal.fire('Updated!', 'Data service telah diperbarui', 'success')
  }

  const addCustomer = async (name, no_telp, alamat) => {
    try {
      await service.addCustomer(name, no_telp, alamat)
      await fetchServiceList(true)
      Swal.fire('Berhasil!', 'Berhasil menambahkan customer', 'success')
    } catch (e) {
      console.error('Gagal menambah user : ', e)
      Swal.fire('Gagal', 'Gagal Menambah Pelanggan', 'error')
    }
  }

  const getCustomer = async () => {
    return await service.getData()
  }

  watch(
    () => service.version,
    async (newVal, oldVal) => {
      if (newVal !== oldVal) {
        console.log('Data changed, refetching service list...')
        await fetchServiceList(true)
      }
    }
  )

  return {
    serviceList: computed(() => service.serviceList),
    loading: computed(() => service.loading),
    error: computed(() => service.error),
    lastFetched: computed(() => service.lastFetched),
    fetchServiceList,
    addCustomer,
    getCustomer,
    refreshCache,
    invalidateCache: service.invalidateCache,
  }
}
