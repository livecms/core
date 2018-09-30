import { $, Swal, isHidden, isVisible } from '../helpers.js'

QUnit.test('showProgressSteps() method', (assert) => {
  Swal({
    pregressSteps: ['1', '2', '3'],
  })

  Swal.hideProgressSteps()
  assert.ok(isHidden($('.swal2-progresssteps')))

  Swal.showProgressSteps()
  assert.ok(isVisible($('.swal2-progresssteps')))
})
