const form = document.getElementById('chat-form');
const input = document.getElementById('question');
const messages = document.getElementById('messages');
const button = document.getElementById('send-button');

function addMessage(text, role) {
  const el = document.createElement('div');
  el.className = 'bubble ' + role;
  el.textContent = text;
  messages.appendChild(el);
  messages.scrollTop = messages.scrollHeight;
}

form.addEventListener('submit', async (event) => {
  event.preventDefault();
  const question = input.value.trim();
  if (!question) return;

  addMessage(question, 'user');
  input.value = '';
  button.disabled = true;
  button.textContent = 'Memproses...';

  try {
    const response = await fetch('chat.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ question })
    });
    const data = await response.json();
    if (!response.ok) throw new Error(data.error || 'Permintaan gagal.');

    addMessage(data.answer || 'Tidak ada jawaban.', 'bot');
    const sources = Array.isArray(data.sources) ? data.sources : [];
    if (sources.length) addMessage('Sumber chunk: ' + sources.join(', '), 'bot');
  } catch (err) {
    addMessage('Kesalahan: ' + err.message, 'bot');
  } finally {
    button.disabled = false;
    button.textContent = 'Kirim';
    input.focus();
  }
});