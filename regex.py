import re
import sys
import json

topic = sys.argv[1]
key = sys.argv[2]

def regex(text, temp):
    word = text.lower();
    spam = temp.lower();
    if re.search(spam, word, re.IGNORECASE):
        # print("Spam!")
        return True;
    else:
        # print("Safe!")
        return False;

#Twitter API + KMP
def spamDetect(topic,keyword):
    #Setup Twitter API
    from twython import Twython
    APP_KEY = 'pVqijAxfbDRTWBadqEHs43Ozm'
    APP_SECRET = '5n0b7DDeCYCqeiHxk2vDNR6gurMcj3mEBAJQ6xIlStShY3R0DR'
    OAUTH_TOKEN = '985071710100598784-NTA548fEqcop86mOBV2bbHUOaDfz3X4'
    OAUTH_TOKEN_SECRET = 'uByR9KjsKdqLL5c1rmcUCmC3G9MNesoWwEuZ1Zf5sskLv'

    twitter = Twython(APP_KEY, APP_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET)

    #From search
    result = twitter.search(q=topic, count=30, lang='id')
    all_tweets = result['statuses']

    spamdata = []
    for tweet in all_tweets: 
        idx = regex(str(tweet['text']).lower(), keyword)
        if (idx):
            spamdata.append('>>>SPAM<<<')
        else:
            spamdata.append(" ")

    data = [ {
        'time': all_tweets[i]['created_at'],
        'id' : all_tweets[i]['id_str'],
        'image': all_tweets[i]['user']['profile_image_url_https'].replace('_normal',''),
        'username': all_tweets[i]['user']['screen_name'],
        'name': all_tweets[i]['user']['name'],
        'text': Twython.html_for_tweet(all_tweets[i]),
        'spam': spamdata[i]
    } for i in range(len(all_tweets))]

    with open('data.txt', 'w') as f:
        json.dump(data, f)

spamDetect(topic,key)