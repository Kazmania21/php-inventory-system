function deleteItem(id) {
    if (!confirm('Are you sure?')) return;

    fetch('api/inventory', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    })
    .then(res => {
      if (!res.ok) {
        throw new Error(res.text());
      }
      else {
        document.getElementById('row-' + id).remove();
      }
    })
    .catch(err => alert(err.message));
}
