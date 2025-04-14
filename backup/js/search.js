document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMが読み込まれました'); // デバッグ用

    // 日付検索フォームの処理
    const dateSearchForm = document.getElementById('dateSearchForm');
    const dateInput = document.getElementById('search_date');
    console.log('検索フォーム:', dateSearchForm); // デバッグ用

    // 日付が変更された時の処理
    if (dateInput) {
        dateInput.addEventListener('change', function() {
            console.log('日付が変更されました');
            performSearch(this.value);
        });
    }

    // フォーム送信時の処理（従来の検索ボタン用）
    if (dateSearchForm) {
        dateSearchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const date = formData.get('search_date');
            performSearch(date);
        });
    }

    // 検索を実行する関数
    function performSearch(date) {
        console.log('検索を実行します:', date);
        
        // 検索結果コンテナを準備
        let resultsContainer = document.querySelector('.meal-list');
        if (!resultsContainer) {
            resultsContainer = document.createElement('div');
            resultsContainer.className = 'meal-list';
            document.querySelector('.search-forms').after(resultsContainer);
        }
        resultsContainer.innerHTML = '<div class="loading">検索中...</div>';

        const formData = new FormData();
        formData.append('search_date', date);
        
        fetch('api/search.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('レスポンスを受信しました:', response); // デバッグ用
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('JSONデータ:', data); // デバッグ用
            updateSearchResults(data);
        })
        .catch(error => {
            console.error('エラーが発生しました:', error); // デバッグ用
            showError('検索中にエラーが発生しました。');
        });
    }

    // 検索結果を更新する関数
    function updateSearchResults(data) {
        console.log('検索結果を更新します:', data); // デバッグ用
        const resultsContainer = document.querySelector('.meal-list');
        console.log('結果コンテナ:', resultsContainer); // デバッグ用
        
        if (!resultsContainer) {
            console.error('結果コンテナが見つかりません');
            return;
        }

        if (data.error) {
            showError(data.error);
            resultsContainer.innerHTML = '';
            return;
        }

        if (!data.records || data.records.length === 0) {
            resultsContainer.innerHTML = '<div class="no-results"><p>その日は記録していないようです。</p></div>';
            return;
        }

        let html = '';
        data.records.forEach(record => {
            // 日付を日本語形式に変換
            const date = new Date(record.date);
            const formattedDate = `${date.getFullYear()}年${date.getMonth() + 1}月${date.getDate()}日`;

            html += `
                <div class="meal-item">
                    <h3>${formattedDate}</h3>
                    <p>朝食：${record.breakfast || '未記録'} 
                       <span class="calories">(${record.breakfast_calories}kcal)</span></p>
                    <p>昼食：${record.lunch || '未記録'} 
                       <span class="calories">(${record.lunch_calories}kcal)</span></p>
                    <p>夕食：${record.dinner || '未記録'} 
                       <span class="calories">(${record.dinner_calories}kcal)</span></p>
                    ${record.snack ? `<p>間食：${record.snack} 
                       <span class="calories">(${record.snack_calories}kcal)</span></p>` : ''}
                </div>
            `;
        });
        console.log('生成されたHTML:', html); // デバッグ用
        resultsContainer.innerHTML = html;
    }

    // エラーメッセージを表示する関数
    function showError(message) {
        console.log('エラーを表示します:', message); // デバッグ用
        let errorContainer = document.querySelector('.error-message');
        if (!errorContainer) {
            errorContainer = document.createElement('div');
            errorContainer.className = 'error-message';
            document.querySelector('.search-forms').after(errorContainer);
        }
        errorContainer.textContent = message;
    }

    // 料理名検索フォームの処理
    const nameSearchForm = document.querySelector('form[action*="search_name"]');
    if (nameSearchForm) {
        nameSearchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            searchRecords(formData);
        });
    }

    // 検索処理を実行する関数
    function searchRecords(formData) {
        console.log('検索処理を開始します'); // デバッグ用
        
        // ローディング表示を追加
        const resultsContainer = document.querySelector('.meal-list') || document.createElement('div');
        resultsContainer.className = 'meal-list';
        resultsContainer.innerHTML = '<div class="loading">検索中...</div>';
        if (!document.querySelector('.meal-list')) {
            document.querySelector('.search-forms').after(resultsContainer);
        }

        fetch('api/search.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('レスポンスを受信しました:', response); // デバッグ用
            return response.json();
        })
        .then(data => {
            console.log('JSONデータ:', data); // デバッグ用
            updateSearchResults(data);
        })
        .catch(error => {
            console.error('エラーが発生しました:', error); // デバッグ用
            showError('検索中にエラーが発生しました。');
        });
    }
}); 