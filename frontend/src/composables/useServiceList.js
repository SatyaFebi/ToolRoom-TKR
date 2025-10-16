import { computed } from 'vue'
import { useServiceListStore } from '@/stores/serviceList'
import Swal from 'sweetalert2'

export default function useServiceList() {
  const service = useServiceListStore()

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
    fetchServiceList: service.fetchServiceList,
    addCustomer,
    getCustomer
  }
}
