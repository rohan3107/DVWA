function show_save_result (data) {
	if (data.result == 'ok') {
		document.getElementById('save_result').innerText = 'Save Successful';
	} else {
		document.getElementById('save_result').innerText = 'Save Failed';
	}
}
	
function submit_change(id) {
	first_name = document.getElementById('first_name_' + id).value
	surname = document.getElementById('surname_' + id).value

	fetch('change_user_details.php', {
		method: 'POST',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
		},
		body: JSON.stringify({ 'id': id, 'first_name': first_name, 'surname': surname })
	}
	)
	.then((response) => response.json())
	.then((data) => show_save_result(data));
}

function escapeHTML(str) {
    return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
}

function populate_form() {
	var xhr= new XMLHttpRequest();
	xhr.open('GET', 'get_user_data.php', true);
	xhr.onreadystatechange= function() {
		if (this.readyState!==4) {
			return;
		}
		if (this.status!==200) {
			return;
		}
		const users = JSON.parse (this.responseText);
		table_body = document.getElementById('user_table').getElementsByTagName('tbody')[0];
		users.forEach(updateTable);

		function updateTable (user) {
			var row = table_body.insertRow(0);
			var cell0 = row.insertCell(-1);
			cell0.innerHTML = escapeHTML(user['user_id']) + '<input type="hidden" id="user_id_' + escapeHTML(user['user_id']) + '" name="user_id" value="' + escapeHTML(user['user_id']) + '" />';
			var cell1 = row.insertCell(1);
			cell1.innerHTML = '<input type="text" id="first_name_' + escapeHTML(user['user_id']) + '" name="first_name" value="' + escapeHTML(user['first_name']) + '" />';
			var cell2 = row.insertCell(2);
			cell2.innerHTML = '<input type="text" id="surname_' + escapeHTML(user['user_id']) + '" name="surname" value="' + escapeHTML(user['surname']) + '" />';
			var cell3 = row.insertCell(3);
			cell3.innerHTML = '<input type="button" value="Update" onclick="submit_change(' + escapeHTML(user['user_id']) + ')" />';
		}
	};
	xhr.send();
}
