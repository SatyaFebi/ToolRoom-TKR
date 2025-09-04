import { computed } from 'vue'
import { useServiceListStore } from '@/stores/serviceList'

export default function useServiceList() {
  const store = useServiceListStore()

  return {
    serviceList: computed(() => store.serviceList),
    loading: computed(() => store.loading),
    error: computed(() => store.error),
    fetchServiceList: store.fetchServiceList,
  }
}
