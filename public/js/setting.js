document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMが読み込まれました'); // デバッグ用ログ
    
    const resetBtn = document.querySelector('.reset-btn');
    const form = document.querySelector('form');
    
    // 各フォーム要素を直接取得
    const heightInput = document.getElementById('height');
    const weightInput = document.getElementById('weight');
    const genderSelect = document.getElementById('gender');
    const ageInput = document.getElementById('age');
    
    console.log('フォーム要素:', {
        height: heightInput,
        weight: weightInput,
        gender: genderSelect,
        age: ageInput
    });
    
    // 指定した初期値を設定
    const initialValues = {
        height: '165.00',
        weight: '60.00',
        gender: '',
        age: '25'
    };
    
    console.log('設定された初期値:', initialValues);
    
    // リセットボタンのクリックイベント
    resetBtn.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('リセットボタンがクリックされました');
        
        // 現在の値をログ出力
        console.log('リセット前の値:', {
            height: heightInput.value,
            weight: weightInput.value,
            gender: genderSelect.value,
            age: ageInput.value
        });
        
        // 値を設定
        heightInput.value = initialValues.height;
        weightInput.value = initialValues.weight;
        genderSelect.value = initialValues.gender;
        ageInput.value = initialValues.age;
        
        // 設定後の値をログ出力
        console.log('リセット後の値:', {
            height: heightInput.value,
            weight: weightInput.value,
            gender: genderSelect.value,
            age: ageInput.value
        });
    });
}); 