import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-resource-progress', IndexField)
  app.component('detail-resource-progress', DetailField)
  app.component('form-resource-progress', FormField)
})
