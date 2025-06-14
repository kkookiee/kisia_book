-- ✅ 문자셋 설정 및 데이터베이스 생성
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

CREATE DATABASE IF NOT EXISTS book_store CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE book_store;

-- 📦 1. users (회원 테이블)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    is_admin TINYINT(1) DEFAULT 0,
    reset_token VARCHAR(255),
    reset_token_expiry DATETIME,
    point INT NOT NULL DEFAULT 0, -- ✅ 보유 포인트 컬럼 추가
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 📘 2. books (도서 테이블)
CREATE TABLE books (
    id VARCHAR(50) NOT NULL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(100),
    description TEXT,
    image_path VARCHAR(255),
    additional_image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 🛒 3. cart (장바구니 테이블)
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id VARCHAR(50) NOT NULL,
    quantity INT DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);

-- 📦 4. orders (주문 테이블)
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_seq INT NOT NULL,
    recipient VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address VARCHAR(255) NOT NULL,
    total_price INT NOT NULL,
    status ENUM('pending', 'paid', 'cancel') DEFAULT 'pending',
    payment_method ENUM('bank_transfer', 'point') DEFAULT 'bank_transfer',
    used_point INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY uq_user_order_seq (user_id, order_seq)
);

-- 📦 5. order_items (주문 상세 테이블)
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    book_id VARCHAR(50) NOT NULL,
    quantity INT NOT NULL,
    price INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);

-- ✍️ 6. reviews (도서 리뷰 테이블)
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    username VARCHAR(255) NOT NULL,
    book_id VARCHAR(50) NOT NULL,
    content TEXT NOT NULL,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    image_path VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
); 

-- 📩 7. inquiries (문의글 테이블)
CREATE TABLE inquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    inquiry_status VARCHAR(50) NOT NULL DEFAULT '답변 대기', 
    answer TEXT,
    answer_at DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 🖼️ 8. inquiries_images (문의글 이미지 테이블)
CREATE TABLE inquiries_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    inquiry_id INT NOT NULL,
    image_path VARCHAR(255),
    FOREIGN KEY (inquiry_id) REFERENCES inquiries(id) ON DELETE CASCADE
);

INSERT INTO users (username, password, name, email, is_admin) 
VALUES
('admin', 'Qwer1234!', '관리자계정', 'admin@admin.com', 1),
('test', 'Qwert1234!', '테스트계정', 'test@test.com', 0);

INSERT INTO books (id, title, author, price, category, description, image_path, additional_image_path) VALUES
('001', '소년이 온다', '한강', 13500, 'novel', '『소년이 온다』는 ‘상처의 구조에 대한 투시와 천착의 서사’를 통해 한강만이 풀어낼 수 있는 방식으로 1980년 5월을 새롭게 조명하며, 무고한 영혼들의 말을 대신 전하는 듯한 진심 어린 문장들로 5·18 이후를 살고 있는 우리에게 묵직한 질문을 던진다.가장 한국적인 서사로 세계를 사로잡은 한강 문학의 지향점을 보여주는 작품. 인간의 잔혹함과 위대함을 동시에 증언하는 이 충일한 서사는 이렇듯 시공간의 한계를 넘어 인간 역사의 보편성을 보여주며 훼손되지 말아야 할 인간성을 절박하게 복원한다.', 'images/book1.jpg', 'images/book1-1.jpg'),
('002', '혼모노', '성해나', 18000, 'novel', '더욱 예리해진 문제의식과 흡인력 넘치는 서사를 통해 지역, 정치, 세대 등 우리를 가르는 다양한 경계를 들여다보며 세태의 풍경을 선명하게 묘파해낸다. 특히 이번 소설집에는 지난해 끊임없이 호명되며 문단을 휩쓸었다 해도 과언이 아닐 표제작 「혼모노」를 비롯해 작가에게 2년 연속 젊은작가상을 선사해준 「길티 클럽: 호랑이 만지기」, 이 계절의 소설과 올해의 문제소설에 선정된 「스무드」 등이 수록되어 더욱 눈길을 끈다. “작가의 ‘신명’이라 불”릴(추천사, 이기호) 만큼 “질투 나는 재능”(추천사, 박정민)으로 빛나는 『혼모노』, 그토록 기다려왔던 한국문학의 미래가 바로 지금 우리 앞에 도착해 있다.', 'images/book2.jpg', 'images/book2-1.jpg'),
('003', '스토너', '존 윌리엄스', 16800, 'novel', '《스토너》는 조용하고 평범한 삶을 살아간 한 남자의 이야기지만, 시간이 지나면서 전 세계적으로 큰 감동을 전한 작품입니다.농부의 아들로 태어난 윌리엄 스토너는 문학을 사랑하며 살아가지만, 그의 삶은 특별한 성공 없이 묵묵히 흘러갑니다. 그러나 그는 누구를 탓하지 않고 자신의 방식으로 슬픔을 받아들이며 살아가고, 이 점이 독자들에게 깊은 공감을 줍니다.50년 동안 잊혔던 이 작품은 유럽에서 큰 반향을 일으키며 베스트셀러가 되었고, 2013년에는 영국 ‘워터스톤’ 올해의 책으로 선정되었습니다. 이제 한국 독자들도 이 늦지만 강렬한 감동을 만나볼 수 있습니다.', 'images/book3.jpg', 'images/book3-1.jpg'),
('004', '급류', '정대건', 14000, 'novel', '『급류』는 저수지와 계곡으로 유명한 ‘진평’을 배경으로, 열일곱 살 도담과 해솔의 만남과 사랑을 담은 이야기다.도담은 수영을 하러 갔다가 물에 빠질 뻔한 해솔을 구하며 인연을 맺고, 둘은 비밀 없이 모든 것을 공유하는 가까운 사이가 된다. 하지만 운명처럼 시작된 이 첫사랑은 예상치 못한 사건으로 인해 급변한다. 해솔의 엄마와 도담의 아빠 사이의 불륜 정황이 드러나면서 도담은 그들을 뒤쫓고, 그날 밤 뜻밖의 사고가 벌어진다.서로가 전부였던 두 사람은 한순간 모든 것이 뒤바뀌고, 그날 밤의 진실이 밝혀지며 이야기는 새로운 국면을 맞이한다.', 'images/book4.jpg', 'images/book4-1.jpg'),
('005', '첫 여름, 완주', '김금희', 17000, 'novel', '김금희 작가의 신작 『완주』는 돈을 갚지 않고 사라진 선배를 찾아 성우 손열매가 완주 마을에 머물며 펼쳐지는 이야기다.열매는 합동 장의사 겸 매점을 운영하는 수미 어머니의 집에서 생활하며 다양한 동네 사람들과 얽히게 된다. 외계인 같은 청년 강동경, 춤을 좋아하는 중학생 한양미, 시고르자브르종 개 샤넬과 함께 사는 배우 정애라 등 개성 넘치는 인물들이 여름 한 철 동안 각자의 삶을 살아간다.', 'images/book5.jpg', 'images/book5-1.jpg'),
('006', '바움카트너', '폴 오스터 ', 17800, 'novel', '〈정원사〉라는 뜻을 가진 그의 성씨와 같이, 바움가트너는 기억의 정원 속 나뭇가지처럼 얽혀 있는 삶의 단편들을 하나씩 찾아간다. 소설은 1968년 뉴욕에서 가난한 문인 지망생으로 아내를 처음 만난 이후 함께한 40년간의 세월, 그리고 뉴어크에서의 어린 시절부터 양장점 주인이자 실패한 혁명가였던 아버지에 대한 회상까지 한 인물의 일생을 톺아보며 그의 내적인 서사를 따라간다. 폴 오스터가 평생 동안 다뤄 왔던 주제인 글쓰기와 허구가 만들어 내는 진실과 힘, 그리고 우연의 미학에 대한 사유가 간결하고 섬세하게 집약된 이 마지막 유작은 죽음 앞에서 써 내려간 상실과 기억에 관한 소설이기에 더욱 절실하고 강렬하다. 이제 폴 오스터라는 소설가를 떠나 보낸 독자들에게 『바움가트너』는 말한다. 〈그게 상상력의 힘이야, 아니, 그냥 간단하게, 꿈의 힘.>', 'images/book6.jpg', 'images/book6-1.jpg'),
('007', '인간 실격', '다자이 오사무무', 9000, 'novel', '다자이 오사무의 『인간 실격』은 인간 사회의 위선과 잔혹성 속에서 파멸해 가는 한 젊은이의 내면을 치밀하게 묘사한 작품이다.주인공은 사회에 융화하려 애쓰지만 결국 모든 것에 배반당하고 인간 실격자로 전락한다. 이는 패전 후 공황 상태에 빠진 일본 젊은이들의 불안과 절망을 반영하며, 기존 가치관이 무너진 시대의 처절한 몸부림을 담고 있다.함께 실린 「직소」는 유다를 ‘나약한 인간’으로 바라보며, 예수를 사랑했지만 거부당한 그의 분노와 갈등을 생생하게 그려낸다.', 'images/book7.jpg', 'images/book7-1.jpg'),
('008', '빛과 실', '한강', 13500, 'novel', '“역사적 트라우마를 정면으로 마주하고 인간 삶의 연약함을 드러내는 강렬하고 시적인 산문”이라는 선정 이유와 함께 2024년 노벨문학상을 수상한 작가 한강의 신작 『빛과 실』(2025)이 문학과지성사 산문 시리즈 「문지 에크리」의 아홉번째 책으로 출간되었다. 노벨문학상 수상 강연문 「빛과 실」(2024)을 포함해 미발표 시와 산문, 그리고 작가가 자신의 온전한 최초의 집으로 ‘북향 방’과 ‘정원’을 얻고서 써낸 일기까지 총 열두 꼭지의 글이, 역시 작가가 기록한 사진들과 함께 묶였다.', 'images/book8.jpg', 'images/book8-1.jpg'),

('021', '미국 주식이 답이다 2026', '장우석, 이향영', 26000, 'economy', '“넥스트 엔비디아, 테슬라를 찾아라!”미주미가 뽑은 2026년을 주도할 미국 유망 기업 대공개★★★★★ 투자자들이 뽑은 해외 주식 멘토 1위 미주미의 개정 신간 ★★★★★★★★★★ Since 2016! 미국 주식 분야 부동의 스테디셀러 ★★★★★★★★★★ 2026년을 주도할 유망 종목 & ETF 29 대공개 ★★★★★여전히, 미국 주식이 답이다!', 'images/book20.jpg', 'images/book20-1.jpg'),
('022', '월 50만 원으로 8억 만드는 배당머신', '평온, 김지형', 19000, 'economy', '“대한민국 1%, 순자산 40억 이상을 보유한 미국 배당주 투자자 ‘평온’의 첫 책이 이나우스북스에서 출간되었다. 이 책에서 경제 상황과 주식에 대한 전문적인 지식 없이도 누구나 배당 투자로 경제적 자유에 이를 수 있는 방법을 제시한다. ‘배당 투자자를 위한 마인드 세팅’, ‘평생 실패하지 않는 투자 전략’, ‘누구든지 쉽게 따라 하는 배당 투자 실전 매매법’, ‘배당 투자 종목 발굴 방법과 실제 사례’까지 총 4부에 걸쳐 배당 투자의 A부터 Z까지 모든 것을 다룬다.', 'images/book21.jpg', 'images/book21-1.jpg'),
('023', '경제신문이 말하지 않는 경제 이야기면', '임주영', 19000, 'economy', '‘세테리스 패러버스’처럼 변수를 무시한 경제적 주장들을 분석하고, 우리가 흔히 듣는 경제 담론을 사실에 근거해 반박한다. GDP 증가, 최저임금, 국민연금 등 익숙한 경제 이슈들을 점검하며, 대격차의 시대를 살아가는 데 필요한 진짜 경제 지식을 제공한다.', 'images/book22.jpg', 'images/book22-1.jpg'),
('024', '트렌드 코리아 2025', '김난도, 전미영, 최지혜, 권정윤, 한다혜', 20000, 'economy', '대한민국은 열풍의 나라이기도 하다. 해외 토픽을 장식한 푸바오 열풍, 마라탕과 탕후루에 이은 두바이 초콜릿 열풍, AI 열풍, 의대 열풍, 스페셜티 커피 열풍, 레트로 열풍, 남녀노소를 가리지 않는 ‘먼작귀’ 열풍까지…. 이 모든 것이 시사하는 바는 무엇인가? 이런 열풍의 이면에 있는 우리 사회 구성원들의 욕망과 결핍은 무엇일까? 『트렌드 코리아 2025』에서 이에 대한 답을 찾아보도록 하자.', 'images/book23.jpg', 'images/book23-1.jpg'),
('025', '듀얼 브레인', '이선', 21000, 'economy', 'AI를 둘러싼 장밋빛 미래와 종말론의 소음을 뚫고, AI라는 동료와 함께 새로운 세상에 적응하는 방법이 무엇인지 알려 주는 실용적인 관점에서 접근한다. 챗GPT를 비롯한 LLM의 특징과 한계에 관해 명확히 알려 주고, AI를 실용적으로 활용하기 위한 원칙과 방법을 설명한다. 그리고 AI가 우리의 미래를 어떻게 바꿀 수 있을지, 그 가능성을 전문적인 시각에서 분석한다.', 'images/book24.jpg', 'images/book24-1.jpg'),
('026', '경제학 콘서트 1', '팀 하포드', 19000, 'economy', '단순히 경제학 지식을 알려주는 것이 아니라, 복잡하고 긴밀하게 연결된 우리의 일상에 경제학이 어떻게 숨어 있는지를 명쾌하게 밝히며 ‘경제학적 사고방식으로 세상을 보는 법’을 알려준 이 책은 “전 국민의 경제 교과서”라는 찬사를 받았다. 나아가 국내는 물론 해외에서도 30개 이상의 언어로 소개되며 세계적인 밀리언셀러로 지금까지 ‘경제학 공부의 바이블’로서의 자리를 여전히 굳건하게 지키고 있다.', 'images/book25.jpg', 'images/book25-1.jpg'),
('027', '우리는 왜 매번 경제위기를 겪어야 하는가?', '론 폴', 23000, 'economy', '미국 900여 개의 대학에 반(反) 연준 자유 운동의 돌풍을 일으킨 책2009년 《뉴욕타임스》 베스트셀러당신의 재산과 자유를 정부와 중앙은행으로부터 지키고 싶다면 읽어야만 하는 필독서진짜 경제학이 가르쳐 주는 중앙은행에 대한 불편한 진실!경제위기가 발생하는 근본 이유를 이론적으로 살펴보며 저자의 의정 경험들과 함께 생생하게 설명하고 있다.', 'images/book26.jpg', 'images/book26-1.jpg'),
('028', '환율의 대전환', '오건영', 27000, 'economy', '그동안은 보지 못했던 큰 변화가 벌어질지 단언할 수 없는 지금이다. 이에 대한민국 최고의 거시경제전문가 오건영 저자는 이번 신간을 통해 지금의 흐릿한 경제 상황을 제대로 바라볼 수 있는 인사이트를 전한다. IMF 외환위기 처음으로 나타난 달러원 환율 1400원, 엔화의 초강세, 연일 최고점을 갱신하는 금 가격까지 지금의 이슈를 만들어 낸 원인을 분석하고, 앞으로의 시나리오를 제시하며 현명한 투자 방법에 대한 조언까지 아끼지 않는다', 'images/book27.jpg', 'images/book27-1.jpg'),
('029', '고향사랑기부제 교과서', '신승근, 조경희', 18000, 'economy', '바꿔야됨', 'images/book28.jpg', 'images/book28-1.jpg'),



('041', '행동하지 않으면 인생은 바뀌지 않는다', '브라이언 트레이시', 16900, 'self_improvement', '당신은 오늘도 ‘갓생’을 외치지만 3일을 버티지 못한다. 매일 밤 동기부여 영상을 보며 감동하지만 아침이면 무너진다. 수많은 자기계발서를 읽었지만 인생은 제자리걸음이다. 왜일까?40년간 워런 버핏, 앤디 그로브와 같은 세계적 대가를 해부해온 브라이언 트레이시가 마침내 진실을 공개한다. 성공한 사람들은 결코 동기부여에 의존하지 않는다는 것. 그들에겐 단 하나의 공통점이 있었다. 바로 ‘아주 작은 행동의 누적’이다.어제보다 1% 더 나아진 행동이 무기력을 쾌감으로 바꾸고 잠자던 성장 본능을 깨우며 마침내 당신을 성공으로 이끈다. 당신의 출신과 환경은 중요하지 않다. 오직 행동만이 당신을 원하는 곳으로 데려갈 것이다.', 'images/book40.jpg', 'images/book40-1.jpg'),
('042', '일의 감각', '조수용', 22000, 'self_improvement', '좋은 감각을 지니려면, 디자인을 잘하려면, 더 나은 브랜드를 만들려면 어떻게 해야 하는가‘일’하는 사람의 섬세한 ‘감각’ 탐구조수용의 첫 단독 에세이한 호에 하나씩, 균형 잡힌 브랜드를 선정하여 그 철학과 감성, 이야기를 소개하는 매거진 『B』의 발행인 조수용의 에세이 『일의 감각』이 출간되었다. 『일의 감각』은 조수용의 첫 단독 저서로, 처음 일을 시작했을 때부터 지금까지 어떤 마음으로 일해왔는지, 디자이너에서 크리에이티브 디렉터, 회사의 대표로 책임의 범위가 넓어지는 동안 어떻게 중심을 잡고 감각을 키워왔는지 그가 진행했던 프로젝트들을 통해 이야기하는 책이다.', 'images/book41.jpg', 'images/book41-1.jpg'),
('043', '외우지 않는 공부법', '손의찬', 18000, 'self_improvement', '외우지 않는 공부법》은 교과서를 읽을 때 단 5분도 집중하지 못했던 수험생에서 최상위권 의대생이 된 저자 손의찬이 수백 가지 공부법을 분석하며 완성한 실전 전략서다. ‘난독증이 아닐까’ 고민할 만큼 공부에 어려움을 겪었던 그는 첫 번째 수능에서 실패한 뒤 기존의 방법으로는 합격할 수 없겠다는 깨달음을 얻었다. 그리고 이번에는 반드시 합격해야 한다는 절박함으로 수능, 공무원 시험, 자격증, 의대 입시까지 다양한 시험 합격자들을 관찰한 후 그들의 공부법을 체계화했다. 이 책에는 그렇게 정리한 합격 공식이 아낌없이 담겨 있다.', 'images/book42.jpg', 'images/book42-1.jpg'),
('044', '불편함에 편안함을 느껴라', '벤 알드리지', 18800, 'self_improvement', '이 책은 단순한 자기계발서가 아니다. 저자가 극심한 불안과 공황을 극복하기 위해 직접 부딪히고, 넘어지고, 다시 일어서며 깨달은 31가지 도전의 기록이다. 그가 경험한 모든 실험은 단순한 모험이 아니라, 두려움을 조련하고, 삶을 더 깊이 이해하며, 정신적으로 단단해지는 과정 그 자체다. 이제 당신의 차례다. 안전지대를 벗어나면, 불안은 더는 장애물이 아니다. 두려움을 정면으로 마주하고, 더 강한 자신을 만나기 위한 여정, 그 길을 이 책과 함께 시작해 보자.', 'images/book43.jpg', 'images/book43-1.jpg'),
('045', '인생을 바꾸는 대화의 기술', '최영준', 18800, 'self_improvement', '이 책의 저자 최영준은 친환경 기업 바이웨이스트(Byewaste)를 설립해 폐기물을 업사이클링하는 혁신적인 사업을 전개하고 있다. 또한, 진로 교육 회사의 프리랜서 수석 강사로서 100회 이상의 강연을 진행하며 ‘말하기’를 통해 사람을 설득하고 관계를 구축하는 법을 연구해 왔다. 단순히 말하기 전문가가 아니라, 직접 말로써 기회를 만들어 온 사업가이자 강연자로서, 그는 이 책을 통해 누구나 쉽게 익히고 실천할 수 있는 말하기 기술을 전하는 데 집중한다. 인생의 기회는 말에서 시작된다고 믿기 때문이다. 말을 바꾸면 인생이 바뀐다!', 'images/book44.jpg', 'images/book44-1.jpg'),
('046', '데일 카네기 자기관리론', '데일 카네기', 11500, 'self_improvement', '데일 카네기의 『자기관리론』은 걱정을 극복하고 삶을 변화시키는 실질적인 원리를 담은 책이다. 걱정이 인생을 좀먹는 가장 큰 원인임을 깨달은 저자는, 이를 해결하기 위해 연구와 강의를 거듭하며 효과적인 방법을 찾았다. 실제 사례를 기반으로 걱정을 다루는 실질적인 해결책을 제시하며, 7년에 걸쳐 집필된 만큼 깊이 있는 내용이 담겨 있다. 『인간관계론』과 함께 자기계발서의 필독서로 손꼽히며, 보다 행복한 삶을 위한 실천법을 알려준다.', 'images/book45.jpg', 'images/book45-1.jpg'),
('047', '이나모리 가즈오, 부러지지 않는 마음', '이나모리 가즈오', 22000, 'self_improvement', '“마음이 꺾인다면, 모든 것이 꺾인 것이다!”‘더 이상은 안 되겠다’는 생각이 들 때 읽는 책80년 경영 인생에서 건져 올린사람과 일, 성공하는 리더십에 대한 깊은 통찰이처럼 강렬하고도 직접적인 그의 메시지들은 이나모리 명예회장의 ‘삶의 자세’에 대한 근본을 관통할 뿐 아니라 불확실한 이 시대를 살고 있는 수많은 사람들에게 여전히 깊은 울림을 선사한다. 인생의 돌파구를 마련하고 싶은 사람이라면 이 책이야말로 의미 깊은 선물이 될 것이다.', 'images/book46.jpg', 'images/book46-1.jpg'),
('048', '진짜 쓰는 실무 엑셀', '전진권', 21000, 'self_improvement', '대기업 직장 생활 10년의 실무 노하우와 엑셀 유튜브 채널을 운영하면서 들은수많은 직장인의 고민을 해결할 수 있는 다양한 엑셀 비법을 담았다.‘진짜 쓰는 실무 엑셀’ 한 권이면 빠른 일 처리로 워라밸을 실현하고, 일잘러로 거듭날 수 있다!', 'images/book47.jpg', 'images/book47-1.jpg'),
('049', '태어나려는 자는 하나의 세계를 깨뜨려야 한다', '김종원', 18800, 'self_improvement', '고정관념, 언어, 두려움, 관계, 일상’이라는 다섯 개의 층위를 하나씩 깨뜨리며, 마침내 ‘낯선 곳의 주인’으로 거듭나는 길을 제시한다. 작가는 헤르만 헤세의 사유를 자기만의 시선으로 해석해, 독자가 자신의 일상 속에서 헤세의 철학을 체득하고 실천할 수 있도록 돕는다. 이 책은 단순한 해설서가 아니다. 삶을 깊이 들여다보게 하는 필사 문장과 흔들리는 내면을 일깨우는 성찰의 메시지, 당장 적용할 수 있는 실천적 조언이 어우러져 독자에게 지적인 전율과 내적 변화를 동시에 선사한다. 철학을 삶 가까이 끌어와, 나의 삶을 다시 써 내려가는 ‘진짜 탄생’의 길로 우리를 이끈다.', '/images/book48.jpg', '/images/book48-1.jpg'),



('061', '이기적 유전자 The Selfish Gene', '리처드 도킨스', 20000, 'science', '과학을 넘어선 우리 시대의 고전, 『이기적 유전자』 40주년 기념판리처드 도킨스의 ‘새로운 에필로그’ 수록새로운 디자인으로 다시 태어난 세계적 베스트셀러이번 40주년 기념판에 새롭게 수록된 에필로그에서 도킨스는 여전히 ‘이기적 유전자’라는 개념이 갖고 있는 지속적인 타당성을 이야기하며 이 책이 전하는 메시지를 되새긴다. 새로운 에필로그를 수록하는 것은 물론 새로운 디자인과 휴대하기 좋은 판형으로 갈아입은 40주년 기념판을 통해 독자들은 『이기적 유전자』가 주는 울림을 더욱더 선명하게 느낄 수 있을 것이다.', '/images/book60.jpg', '/images/book60-1.jpg'),
('062', '물고기는 존재하지 않는다', '룰루 밀러', 17000, 'science', '‘방송계의 퓰리처상’으로 불리는 피버디상을 수상한 과학 전문기자 룰루 밀러의 경이로운 논픽션 《물고기는 존재하지 않는다》는 여러 언론 매체에서 ‘2020년 최고의 책’으로 선정할 만큼 수많은 찬사를 받은 화제의 베스트셀러다. 집착에 가까울 만큼 자연계에 질서를 부여하려 했던 19세기 어느 과학자의 삶을 흥미롭게 좇아가는 이 책은 어느 순간 독자들을 혼돈의 한복판으로 데려가서 우리가 믿고 있던 삶의 질서에 관해 한 가지 의문을 제기한다. “물고기가 존재하지 않는다는 것은 엄연한 하나의 사실이다. 그렇다면 우리는 또 무엇을 잘못 알고 있을까?” 하고 말이다. 누군가에게는 이 질문이 살아가는 데 아무런 영향을 미치지 않을 수도 있다. 하지만 세상을 바라보는 “진실한 관계들”에 한층 가까이 다가가기 위해 노력하는 사람들에게는 분명 이 책이 놀라운 영감과 어느 한쪽으로도 치우치지 않는 폭넓은 시야를 제공해줄 것이다.', '/images/book61.jpg', '/images/book61-1.jpg'),
('063', '무의식은 어떻게 나를 설계하는가', '데이비드 이글먼', 25000, 'science', '★ 뉴욕타임스 베스트셀러, 보스턴 글로브 올해의 책 ★뇌과학계의 칼 세이건, 데이비드 이글먼 연구의 첫걸음“우리가 뇌에 대해 궁금해하는 질문들에 관해 현대 뇌과학이 내놓은 해답.”뇌과학자 정재승 추천!오늘 했던 행동이 정말 내가 한 게 맞을까? 어떤 일들은 의식하지 못하는 사이에 일어나곤 한다. 도어락 비밀번호를 눌러 문을 열고, 운전을 해서 출근하는 행위 같은 일상적인 행동에서부터, 가끔 ‘이걸 내가?’ 싶은 멋진 글을 써내기도 한다. 괴테가 『젊은 베르테르의 슬픔』을 쓸 때 “손에 쥔 펜이 저절로 움직이는 것 같았다”고 했던 것이나 지드래곤이 〈This love〉를 작사하는 데 20분도 채 걸리지 않았다며 스스로 놀라움을 표현한 일 모두, 그 중심에는 ‘무의식’이 있다.', '/images/book62.jpg', '/images/book62-1.jpg'),
('064', '야구x수학', '류선규, 홍석만', 24000, 'science', 'KBO 공식 추천도서! 스타강사 정승제 추천도서!야구장에서 수학을 만나다야구와 수학이 만났다. 한쪽은 뜨거운 열정이, 다른 한쪽은 차가운 이성이 깃든 분야지만 두 세계는 ‘숫자’라는 공통된 언어로 닿아 있다. 『수학을 품은 야구공』으로 큰 반향을 일으켰던 홍석만 수학교사와 류선규 전 SK와이번스 단장이 만나 야구의 수많은 순간을 ‘수학’의 눈으로 들여다본다. 현직 수학교사와 야구 전문가가 힘을 합쳐 만든 이 책은 수학적 정밀함과 현장의 생동감을 모두 품고 있다.', '/images/book63.jpg', '/images/book63-1.jpg'),
('065', '파인만의 여섯가지 물리 이야기', '리처드 필립 파인만', 12000, 'science', '', '/images/book64.jpg', '/images/64-1.jpg'),
('066', '지능의 기원', '맥스 베넷', 33000, 'science', '맥스 베넷의 『지능의 기원』은 인류의 뇌가 어떻게 진화했으며, 이를 통해 AI가 탄생하게 된 과정을 탐구하는 책이다.뇌는 단순한 기능에서 시작해 반복 학습, 상상, 짐작, 언어 사용 등 다섯 번의 혁신을 거쳤으며, 이러한 과정이 인간 지능뿐만 아니라 AI의 발전에도 중요한 역할을 했다. 저자는 신경과학과 진화적 관점에서 지능의 본질을 분석하며, 인공지능의 미래를 내다본다.뇌과학과 AI의 관계를 깊이 이해하고 싶은 독자들에게 새로운 통찰을 제공하는 이 책은 과학적 호기심과 실용적인 접근을 결합해 미래를 전망한다.', '/images/book65.jpg', '/images/book65-1.jpg'),
('067', '게으른 자를 위한 수상한 화학책', '이광렬', 17500, 'science', '이 책을 통해 화학이 대신 일해 주는 동안, 나는 더 가치 있는 것(예를 들어 차를 마시며 음악을 듣거나 소중한 사람과 함께 시간을 보내는 일)을 하며 마음껏 게을러지는 경험을 하게 될 것이다. 게으른 자가 더 게을러지기 위해 화학을 공부하는 역설적인 상황이지만, 지식과 시간을 동시에 내 것으로 만들 수 있다면 공부의 가치는 충분할 것이다. 화학자가 마음먹고 청소에 덤벼들면 어떤 일이 벌어지는지 알고 싶다면, 생활과 공부 모두 잡는 화학의 힘이 궁금하다면, 부담 없이 읽을 수 있는 화학 교양서를 찾고 있다면 이 책을 펼칠 것!', '/images/book66.jpg', '/images/book66-1.jpg'),
('068', '블랙홀', '브라이언 콕스, 제프 포셔', 33000, 'science', '‘블랙홀’은 누구나 알지만 아무도 모르는 존재다. “블랙홀을 알기 위해서는 물리의 거의 모든 내용을 알아야 한다”고 할 만큼, 블랙홀은 물리학, 천문학 등을 공부할 때 절대 빼놓을 수 없고, 블랙홀을 통하지 않고서는 우주에 진입할 수 없다. BBC 과학 다큐멘터리 〈경이로운 우주〉 〈경이로운 생명〉 등에 출연하면서 유명해진 브라이언 콕스는 과학의 신비를 대중에게 알리는 데 중요한 역할을 하며 “차세대 칼 세이건”이라는 명성을 얻은 물리학자다. 같은 대학에서 입자물리학을 가르치는 제프 포셔와 함께 연구를 진행하며 그간 『퀀텀 유니버스』 『E=mc2 이야기』 등 몇 권의 베스트셀러를 출간했다. 두 물리학자의 연구가 이번에는 블랙홀을 향한다.', '/images/book67.jpg', '/images/book67-1.jpg'),



('081', '혼자 공부하는 파이썬', '윤인성', 22000, 'computer_it', '『혼자 공부하는 파이썬』이 더욱 흥미있고 알찬 내용으로 개정되었다. 프로그래밍이 정말 처음인 입문자도 따라갈 수 있는 친절한 설명과 단계별 학습은 그대로! 혼자 공부하더라도 체계적으로 계획을 세워 학습할 수 있도록 ‘혼공 계획표’를 새롭게 추가했다. 또한 입문자가 자주 물어보는 질문과 오류 해결 방법을 적재적소에 배치하여 예상치 못한 문제에 부딪혀도 좌절하지 않고 끝까지 완독할 수 있도록 도와준다. 단순한 문법 암기와 코딩 따라하기에 지쳤다면, 새로운 혼공파와 함께 ‘누적 예제’와 ‘도전 문제’로 프로그래밍의 신세계를 경험해 보자! 배운 내용을 씹고 뜯고 맛보고 즐기다 보면 응용력은 물론 알고리즘 사고력까지 키워 코딩 실력이 쑥쑥 성장할 것이다.', '/images/book80.jpg', '/images/book80-1.jpg'),
('082', '혼자 공부하는 컴퓨터구조+운영체제', '강민철', 28000, 'computer_it', '이 책은 독학으로 컴퓨터 구조와 운영체제를 배우는 입문자가 ‘꼭 필요한 내용을 제대로 학습’할 수 있도록 구성했다. 뭘 모르는지조차 모르는 입문자의 막연한 마음에 십분 공감하여 과외 선생님이 알려주듯 친절하게, 핵심 내용만 콕콕 집어 준다. 『컴푸터 구조』편에서는 컴퓨터를 이루고 있는 부품들과 각 부품의 역할을 알아본다. 또한 컴퓨터 내부의 구조와 작동법을 이해하고, 컴퓨터가 어떻게 명령어를 처리하는지 학습한다. 『운영체제』편에서는 운영체제의 필요성을 배운 뒤 앞서 배운 컴퓨터의 부품들을 운영체제가 어떻게 사용하는지 전체 과정을 살펴본다.', '/images/book81.jpg', '/images/book81-1.jpg'),
('083', '이게 되네? 챗GPT 미친 활용법 71제', '오힘찬', 24000, 'computer_it', '아직도 ‘챗GPT로 어떤 것까지 할 수 있을까? 유료 구독을 할 가치가 있을까?’ 고민하는가? 챗GPT를 단순히 신기한 AI가 아닌 실생활에 ‘진짜’ 활용하고 싶은가? 이 책으로 그 답을 얻어보자. 누구보다 더 효과적으로 더 적극적으로 챗GPT를 활용하는 방법을 알려주는 이 책이 인공지능 시대에 일잘러, 작가, 영상 편집자, 현인으로서 살아갈 여러분의 마법의 지팡이가 되어줄 것이다.', '/images/book82.jpg', '/images/book82-1.jpg'),
('084', '된다! 조회수 터지는 유튜브 쇼츠 만들기', '최지영', 22000, 'computer_it', '『된다! 조회수 터지는 유튜브 쇼츠 만들기』는 유튜브 쇼츠를 처음 시작하는 분들을 위해 ‘첫 영상 올리기’부터 ‘수익화’까지 전 과정을 담은 입문서이다. 유튜브가 밀어 주는 키워드와 주제를 발굴해 채널의 방향성과 전략을 구사할 수 있도록 다양한 벤치마킹 방법과 챗GPT 활용법을 소개한다. 또, 이 책에서 배우는 AI 브루 활용법과 간단한 캡컷 편집 기술만 알면 내가 평소 보던 쇼츠 영상 그 이상의 것도 만들 수 있다. 유튜브 알고리즘을 관리해서 만든 영상이 지속적으로 사랑받고 노출되는 채널 운영 노하우까지 전수해 준다. 아울러 쇼츠 영상을 릴스와 틱톡으로 확장하여 단기간 내에 한층 더 영향력 있는 인플루언서로 성장할 수도 있는 기반을 마련하는 과정과 제2의 월급을 만들어 주는 수익화 방법 4가지까지 해답을 얻어 갈 수 있다. 쇼츠, 이 책과 함께 지금 당장 도전하자!', '/images/book83.jpg', '/images/book83-1.jpg'),
('085', '주니어 백엔드 개발자가 반드시 알아야 할 실무 지식', '최범균', 28000, 'computer_it', '서비스 환경에서는 커넥션을 닫지 않아 서버가 멈추고 외부 API의 지연이 전체 장애로 번지며 사소한 설정 실수가 사용자 전체에 영향을 주는 일이 실제로 발생한다. 『주니어 백엔드 개발자가 반드시 알아야 할 실무 지식』은 주니어 백엔드 개발자가 실제 현장에서 자주 마주치는 문제들을 스스로 이해하고 해결할 수 있도록 돕는 실무 밀착 가이드다. 겉보기엔 잘 돌아가는 서비스라도 규모가 커지고 사용자가 늘어나면 언제든 위기 상황에 직면할 수 있다. 이 책은 성능 저하, DB 연결 오류, 비동기 연동 문제, 동시성 제어, 인프라 운영, 보안 취약점 등 서비스 운영 과정에서 겪게 되는 핵심 이슈를 살펴보면서 왜 이런 문제가 발생하는지, 어떻게 대응해야 하는지를 체계적으로 알려준다. 이 책으로 실무에서의 혼란과 시행착오를 줄이고 서비스 운영 과정에서 발생할 여러 문제를 예방하거나 해결하는 역량을 키울 수 있을 것이다.', '/images/book84.jpg', '/images/book84-1.jpg'),
('086', 'Do it! 점프 투 파이썬', '박응용', 22000, 'computer_it', '프로그래밍 분야 8년 연속 베스트셀러!『Do it! 점프 투 파이썬』 전면 개정 2판 출시!중고등학생도, 비전공자도, 직장인도 프로그래밍에 눈뜨게 만든 바로 그 책이 전면 개정 2판으로 새롭게 태어났다! 챗GPT를 시작으로 펼쳐진 생성 AI 시대에 맞춰 설명과 예제를 다듬고, 최신 경향과 심화 내용을 보충했다. 또한 이번 개정 2판도 50만 코딩 유튜버인 조코딩과 협업을 통해 유튜브 동영상을 제공해 파이썬을 더 쉽게 공부할 수 있다.', '/images/book85.jpg', '/images/book85-1.jpg'),
('087', '기계는 왜 학습하는가', '아닐 아난타스와미', 25000, 'computer_it', '2024년 챗GPT의 마법 같은 등장은 빠르게 모두의 관심사를 장악했다. AI가 길을 찾아주고, 음악을 추천하고, 그림을 그려주고, 문서를 정리해주는 수준에서 도약하여 정보를 “스스로” 찾아서 알려주고 질문에 “생각해서” 대답하는 수준에 이른 것처럼 보였기 때문이다. 과연 AI는 진짜 생각하는 기계가 된 것인가? AI로 인해서 우리 사회는 어떻게 달라지고, 우리의 삶은 또한 어떻게 될 것인가에 대한 장밋빛 기대와 어두운 우려가 공존하고 있다. 과학저술가 아닐 아난타스와미의 이 책은 오늘날의 AI를 있게 한 알고리즘을 구성하는 핵심 수학을 상세하게 살펴봄으로써 기계 안에서 어떤 과정이 작동하고 있는지를 선명하게 제시한다.', '/images/book86.jpg', '/images/book86-1.jpg'),
('088', '챗GPT 일타강사의 직장인 업무 만렙 공략집', '이승필', 24000, 'computer_it', '프로 일잘러들만 몰래 쓰던 챗GPT 업무 활용 노하우가 드디어 책으로 공개된다. 120개 기업이 앞다퉈 섭외하고 직장인 2만 명이 극찬한 ‘챗GPT 일타강사’의 초실전 업무 공략법을 한 권에 압축했다. 이 책은 단순한 AI 사용법이 아닌, 진짜 업무 성과로 직결되는 극강의 예제만을 안내한다. 이메일과 보고서·기획서 작성은 기본, 엑셀 자동화, PDF 편집, PPT 제작, 자동화 봇 생성까지 평소 막대한 시간과 에너지를 소모해야 했던 업무들을 단 몇 분, 심지어 몇 초 만에 끝낼 수 있게 해준다. 이 책과 함께라면 퇴근은 빨라지고, 성과는 폭발하며, 누구나 인정하는 ‘일 잘하는 사람’으로 완벽히 거듭날 수 있다. 지금, 대한민국에서 가장 많은 기업과 직장인이 선택한 챗GPT 1급 특강을 만나볼 차례다.', '/images/book87.jpg', '/images/book87-1.jpg'),

('100', '흔한남매 세계사 탐험대 3 로마 제국', '흔한 남매', 14800,'children', '『흔한남매 세계사 탐험대』 시리즈는 세계사를 처음 배우는 초등학생을 위한 세계사 입문 학습만화이다. 어린이들이 세계사의 흐름을 자연스럽게 이해할 수 있도록 시간순으로 세계사의 주요 사건들을 선별하고, 흔한남매의 흥미진진한 이야기와 엮어 기억에 오래 남도록 구성했다. 또 방대한 용어들 중에 먼저 알아야 할 키워드들을 제시하여 세계사 공부에 대한 부담을 줄였다. 현직 역사 선생님들이 직접 꼽은 ‘세계사 필수 키워드 300’ 카드를 제공해 아이들이 세계사 키워드를 놀이처럼 접할 수 있게 했고, 각 권에서 다루고 있는 지역을 상세히 담은 ‘세계사 탐험 지도’를 통해 공간 감각도 키울 수 있다. 최고의 역사 전문가들이 머리를 맞대고 만들어 더욱 믿을 수 있는 『흔한남매 세계사 탐험대』와 함께 이번에도 흥미진진한 세계사 모험을 떠나 보자!', '/images/book100.jpg', '/images/book100-1.jpg');