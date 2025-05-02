CREATE TABLE IF NOT EXISTS books (
    id VARCHAR(50) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(100),
    description TEXT,
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 샘플 데이터 추가
INSERT INTO books (id, title, author, price, category, description, image_path) VALUES
('novel_001', '해리포터와 마법사의 돌', 'J.K. 롤링', 15000.00, '소설', '마법 세계로의 첫 번째 모험', 'images/harry_potter.jpg'),
('novel_002', '아몬드', '손원평', 13000.00, '소설', '감정을 느끼지 못하는 소년의 이야기', 'images/almond.jpg'),
('self_001', '미움받을 용기', '기시미 이치로', 14000.00, '자기계발', '아들러 심리학을 통한 인생의 해답', 'images/courage.jpg'),
('self_002', '죽음의 수용소에서', '빅터 프랭클', 16000.00, '자기계발', '인간의 의미를 찾아서', 'images/man_search.jpg'),
('it_001', 'Clean Code', '로버트 C. 마틴', 35000.00, '컴퓨터/IT', '클린 코드 작성법', 'images/clean_code.jpg'),
('it_002', '혼자 공부하는 파이썬', '윤인성', 22000.00, '컴퓨터/IT', '파이썬 입문서', 'images/python.jpg'),
('eco_001', '부의 추월차선', '엠제이 드마코', 18000.00, '경제/경영', '부자 되는 방법', 'images/fastlane.jpg'),
('eco_002', '돈의 심리학', '모건 하우절', 17000.00, '경제/경영', '돈에 대한 심리학적 접근', 'images/psychology_money.jpg'),
('sci_001', '코스모스', '칼 세이건', 20000.00, '과학기술', '우주에 대한 탐구', 'images/cosmos.jpg'),
('sci_002', '시간의 역사', '스티븐 호킹', 25000.00, '과학기술', '우주의 기원과 진화', 'images/brief_history.jpg'),
('used_001', '중고 해리포터 세트', 'J.K. 롤링', 50000.00, '중고도서', '1-7권 완결 세트, 상태 양호', 'images/used_harry_potter.jpg'),
('used_002', '중고 Clean Code', '로버트 C. 마틴', 20000.00, '중고도서', '약간의 필기 있음, 상태 양호', 'images/used_clean_code.jpg'),
('used_003', '중고 코스모스', '칼 세이건', 12000.00, '중고도서', '겉표지 약간 마모, 내지 상태 양호', 'images/used_cosmos.jpg'); 