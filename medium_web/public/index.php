<!doctype html>
<html lang="zh-TW">
<head>
<meta charset="utf-8">
<title>XML Viewer</title>
<style>
body {
 font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
 background: #f0f2f5;
 display: flex;
 justify-content: center;
 align-items: center;
 min-height: 100vh;
 margin: 0;
 padding: 20px;
 box-sizing: border-box;
}
.container {
 background: #fff;
 padding: 30px;
 border-radius: 12px;
 box-shadow: 0 8px 20px rgba(0,0,0,0.1);
 width: 100%;
 max-width: 600px;
 text-align: center;
}
h1 {
 margin-bottom: 20px;
 color: #333;
}
.file-input-wrapper {
 margin-bottom: 15px;
}
input[type="file"] {
 padding: 10px;
 border: 2px dashed #007BFF;
 border-radius: 8px;
 background: #f8f9fa;
 width: 100%;
 box-sizing: border-box;
}
button {
 padding: 10px 20px;
 background: #007BFF;
 color: #fff;
 border: none;
 border-radius: 8px;
 cursor: pointer;
 font-size: 16px;
 transition: background 0.3s;
}
button:hover {
 background: #0056b3;
}
button:disabled {
 background: #ccc;
 cursor: not-allowed;
}
.result-container {
 margin-top: 20px;
}
pre {
 text-align: left;
 background: #f8f9fa;
 padding: 15px;
 border-radius: 8px;
 max-height: 400px;
 overflow-y: auto;
 white-space: pre-wrap;
 word-break: break-word;
 border: 1px solid #e9ecef;
}
.error {
 color: #dc3545;
 background: #f8d7da;
 border-color: #f5c6cb;
}
.success {
 color: #155724;
 background: #d4edda;
 border-color: #c3e6cb;
}
.loading {
 color: #856404;
 background: #fff3cd;
 border-color: #ffeaa7;
}
.auth-message {
 margin-top: 10px;
 padding: 10px;
 border-radius: 5px;
 font-size: 14px;
}
</style>
</head>
<body>
<div class="container">
 <h1>XML Viewer</h1>
 
 <div id="authStatus" class="auth-message" style="display: none;"></div>
 
 <form id="xmlForm">
  <div class="file-input-wrapper">
   <input type="file" id="xmlFile" accept=".xml,.txt">
  </div>
  <button type="submit" id="submitBtn">Upload and Parse</button>
 </form>
 
 <div class="result-container">
  <pre id="result">Select an XML file and click "Upload and Parse" to view the formatted XML.</pre>
 </div>
</div>

<script>
window.addEventListener('load', async () => {
 try {
  const response = await fetch('api.php', {
   method: 'GET',
   credentials: 'include'
  });
  
  const authStatus = document.getElementById('authStatus');
  
  if (response.status === 401) {
   authStatus.textContent = 'Authentication required. Redirecting to login...';
   authStatus.className = 'auth-message error';
   authStatus.style.display = 'block';
   
   setTimeout(() => {
    window.location.href = 'login.php';
   }, 2000);
   return;
  }
  
  if (response.ok) {
   authStatus.textContent = 'Authentication successful';
   authStatus.className = 'auth-message success';
   authStatus.style.display = 'block';
   
   setTimeout(() => {
    authStatus.style.display = 'none';
   }, 3000);
  }
 } catch (err) {
  console.error('Authentication check failed:', err);
  const authStatus = document.getElementById('authStatus');
  authStatus.textContent = 'Authentication check failed. Redirecting to login...';
  authStatus.className = 'auth-message error';
  authStatus.style.display = 'block';
  
  setTimeout(() => {
   window.location.href = 'login.php';
  }, 2000);
 }
});

const form = document.getElementById('xmlForm');
const result = document.getElementById('result');
const submitBtn = document.getElementById('submitBtn');

form.addEventListener('submit', async (e) => {
 e.preventDefault();
 
 const fileInput = document.getElementById('xmlFile');
 if (!fileInput.files.length) {
  alert('Please select an XML file');
  return;
 }
 
 const file = fileInput.files[0];
 
 submitBtn.disabled = true;
 submitBtn.textContent = 'Processing...';
 result.textContent = 'Processing XML file...';
 result.className = 'loading';
 
 try {
  const content = await file.text();
  
  const response = await fetch('api.php', {
   method: 'POST',
   headers: { 'Content-Type': 'application/xml' },
   body: content,
   credentials: 'include'
  });
  
  if (response.status === 401) {
   result.textContent = 'Authentication failed. Redirecting to login...';
   result.className = 'error';
   setTimeout(() => {
    window.location.href = 'login.php';
   }, 2000);
   return;
  }
  
  const text = await response.text();
  
  if (response.ok) {
   result.textContent = text;
   result.className = 'success';
  } else {
   result.textContent = 'Error: ' + text;
   result.className = 'error';
  }
  
 } catch (err) {
  result.textContent = 'Error: ' + err.message;
  result.className = 'error';
 } finally {
  submitBtn.disabled = false;
  submitBtn.textContent = 'Upload and Parse';
 }
});

document.getElementById('xmlFile').addEventListener('change', function(e) {
 const file = e.target.files[0];
 if (file) {
  result.textContent = `Selected file: ${file.name} (${(file.size / 1024).toFixed(2)} KB)`;
  result.className = '';
 }
});
</script>
</body>
</html>